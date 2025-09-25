<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="referrer" content="no-referrer-when-downgrade">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/smcodi-icon-white.png" type="image/png" sizes="32x32">
        <link rel="icon" href="/smcodi-icon-white.png" type="image/png" sizes="16x16">
        <link rel="apple-touch-icon" href="/smcodi-icon-white.png" sizes="180x180">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        {{--    Google one tap    --}}
        <script src="https://accounts.google.com/gsi/client" async="" defer=""></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
        <x-google_one_tap.onload/>
    </body>
</html>
