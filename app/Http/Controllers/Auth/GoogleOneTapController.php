<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleOneTapRequest;
use App\Models\User;
use GoogleOneTap\Services\GoogleOneTapService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleOneTapController extends Controller
{
    /**
     * Handle Google One Tap callback
     */
    public function callback(GoogleOneTapRequest $request): JsonResponse|RedirectResponse
    {
        try {
            Log::channel('auth')->info('Google One Tap callback initiated', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
                'request_data' => $request->all(),
            ]);

            // Request is already validated by GoogleOneTapRequest

            // Get token from Google One Tap service
            $token = GoogleOneTapService::getToken($request);

            if (!$token) {
                Log::channel('auth')->error('Failed to get token from Google One Tap service', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                return response()->json(['error' => 'Invalid token'], 400);
            }

            Log::channel('auth')->info('Google token retrieved successfully');

            // Get user from Google
            $googleUser = Socialite::driver('google-one-tap')->stateless()->userFromToken($token);

            if (!$googleUser) {
                Log::channel('auth')->error('Failed to get user from Google', [
                    'token_length' => strlen($token),
                ]);

                return response()->json(['error' => 'Failed to authenticate with Google'], 400);
            }

            Log::channel('auth')->info('Google user data retrieved', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
            ]);

            // Handle user creation/login
            $user = $this->handleGoogleUser($googleUser);

            // Login the user
            Auth::login($user, true); // Remember user

            $request->session()->regenerate();

            Log::channel('auth')->info('User logged in successfully via Google One Tap', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'session_id' => $request->session()->getId(),
                'session_data' => $request->session()->all(),
                'auth_check_after_login' => Auth::check(),
            ]);

            // Return success response for AJAX or redirect for regular request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => $this->getIntendedUrl()
                ]);
            }

            return redirect()->intended(route('home', absolute: false));

        } catch (\Exception $e) {
            Log::channel('auth')->error('Google One Tap authentication failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Authentication failed'], 500);
            }

            return redirect()->route('login')->withErrors(['email' => 'Authentication failed. Please try again.']);
        }
    }

    /**
     * Handle Google user - create or update existing user
     */
    protected function handleGoogleUser($googleUser): User
    {
        $email = $googleUser->getEmail();

        Log::channel('auth')->info('Processing Google user', [
            'email' => $email,
            'google_id' => $googleUser->getId(),
        ]);

        // Check if user exists by email
        $user = User::where('email', $email)->first();

        if ($user) {
            // Update existing user
            $user = $this->updateExistingUser($user, $googleUser);

            Log::channel('auth')->info('Existing user updated', [
                'user_id' => $user->id,
                'email' => $email,
            ]);
        } else {
            // Create new user
            $user = $this->createNewUser($googleUser);

            Log::channel('auth')->info('New user created from Google One Tap', [
                'user_id' => $user->id,
                'email' => $email,
            ]);
        }

        return $user;
    }

    /**
     * Create new user from Google data
     */
    protected function createNewUser($googleUser): User
    {
        return User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'email_verified_at' => now(), // Google emails are verified
            'password' => Hash::make(Str::random(32)), // Random password since they use Google
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
        ]);
    }

    /**
     * Update existing user with Google data
     */
    protected function updateExistingUser(User $user, $googleUser): User
    {
        $updateData = [];

        // Update Google ID if not set
        if (!$user->google_id) {
            $updateData['google_id'] = $googleUser->getId();
        }

        // Update avatar if not set or different
        if (!$user->avatar || $user->avatar !== $googleUser->getAvatar()) {
            $updateData['avatar'] = $googleUser->getAvatar();
        }

        // Verify email if not verified
        if (!$user->email_verified_at) {
            $updateData['email_verified_at'] = now();
        }

        if (!empty($updateData)) {
            $user->update($updateData);

            Log::channel('auth')->info('User data updated', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($updateData),
            ]);
        }

        return $user;
    }

    /**
     * Get intended URL for redirect
     */
    protected function getIntendedUrl(): string
    {
        return session()->pull('url.intended', route('dashboard', absolute: false));
    }
}
