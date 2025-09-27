<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import CreatePostModal from './CreatePostModal.vue';

// Props để điều khiển trạng thái active của navigation
interface Props {
    activeTab?: 'home' | 'explore' | 'discuss';
}

const props = withDefaults(defineProps<Props>(), {
    activeTab: 'home'
});

// Mobile menu state
const isMobileMenuOpen = ref(false);

// Modal state
const isModalOpen = ref(false);

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const openModal = () => {
    isModalOpen.value = true;
    // Đóng mobile menu nếu đang mở
    isMobileMenuOpen.value = false;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const handleSubmit = (data: any) => {
    console.log('Post data from Navigation:', data);
    // TODO: Xử lý submit data ở đây
    closeModal();
    // Có thể emit event hoặc call API
};
</script>

<template>
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 w-full bg-white/72 backdrop-blur-[20px] backdrop-saturate-[180%] z-[1000] border-b border-black/8 dark:bg-black/72 dark:border-white/10">
        <div class="max-w-[980px] mx-auto px-5 h-12 flex items-center justify-between">
            <!-- Logo -->
            <Link href="/" class="text-lg font-semibold text-black dark:text-white tracking-[-0.5px] cursor-pointer">
                CodeCraft
            </Link>

            <!-- Desktop Navigation Menu -->
            <div class="hidden md:flex gap-8 items-center">
                <Link
                    href="/"
                    :class="[
                        'text-sm transition-all duration-200 font-normal',
                        activeTab === 'home'
                            ? 'text-black dark:text-white'
                            : 'text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white'
                    ]"
                >
                    Trang chủ
                </Link>

                <Link
                    href="/explore"
                    :class="[
                        'text-sm transition-all duration-200 font-normal',
                        activeTab === 'explore'
                            ? 'text-black dark:text-white'
                            : 'text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white'
                    ]"
                >
                    Khám phá
                </Link>

                <Link
                    href="/discuss"
                    :class="[
                        'text-sm transition-all duration-200 font-normal',
                        activeTab === 'discuss'
                            ? 'text-black dark:text-white'
                            : 'text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white'
                    ]"
                >
                    Thảo luận
                </Link>

                <!-- Post Button -->
                <button
                    @click="openModal"
                    class="bg-black dark:bg-white text-white dark:text-black border-none px-3.5 py-1.5 rounded-md text-[13px] cursor-pointer transition-all duration-200 font-medium hover:opacity-80"
                >
                    Đăng bài
                </button>
            </div>

            <!-- Mobile Menu -->
            <div class="md:hidden flex items-center gap-3">
                <!-- Post Button Mobile -->
                <button
                    @click="openModal"
                    class="bg-black dark:bg-white text-white dark:text-black border-none px-3 py-1.5 rounded-md text-[13px] cursor-pointer transition-all duration-200 font-medium hover:opacity-80"
                >
                    Đăng bài
                </button>

                <!-- Hamburger Menu Button -->
                <button
                    @click="toggleMobileMenu"
                    class="p-2 text-black dark:text-white"
                >
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div
            v-if="isMobileMenuOpen"
            class="md:hidden absolute top-full left-0 right-0 bg-white/95 dark:bg-black/95 backdrop-blur-[20px] border-b border-black/8 dark:border-white/10"
        >
            <div class="max-w-[980px] mx-auto px-5 py-4 space-y-3">
                <Link
                    href="/"
                    @click="isMobileMenuOpen = false"
                    :class="[
                        'block text-sm transition-all duration-200 font-normal py-2',
                        activeTab === 'home'
                            ? 'text-black dark:text-white font-medium'
                            : 'text-gray-600 dark:text-gray-400'
                    ]"
                >
                    Trang chủ
                </Link>

                <Link
                    href="/explore"
                    @click="isMobileMenuOpen = false"
                    :class="[
                        'block text-sm transition-all duration-200 font-normal py-2',
                        activeTab === 'explore'
                            ? 'text-black dark:text-white font-medium'
                            : 'text-gray-600 dark:text-gray-400'
                    ]"
                >
                    Khám phá
                </Link>

                <Link
                    href="/discuss"
                    @click="isMobileMenuOpen = false"
                    :class="[
                        'block text-sm transition-all duration-200 font-normal py-2',
                        activeTab === 'discuss'
                            ? 'text-black dark:text-white font-medium'
                            : 'text-gray-600 dark:text-gray-400'
                    ]"
                >
                    Thảo luận
                </Link>
            </div>
        </div>
    </nav>

    <!-- Create Post Modal -->
    <CreatePostModal
        :is-open="isModalOpen"
        @close="closeModal"
        @submit="handleSubmit"
    />
</template>


