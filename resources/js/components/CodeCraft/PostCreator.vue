<script setup lang="ts">
import { ref } from 'vue';
import CreatePostModal from './CreatePostModal.vue';

// Component khung tạo bài viết mới
const isModalOpen = ref(false);

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const handleSubmit = (data: any) => {
  console.log('Post data:', data);
  // TODO: Xử lý submit data ở đây
  closeModal();
  // Có thể emit event hoặc call API
};
</script>

<template>
    <!-- Post Creator -->
    <div class="bg-white dark:bg-black border border-gray-200 dark:border-white/10 rounded-xl p-4 mb-6">
        <div class="flex gap-3 mb-3">
            <!-- Creator Avatar -->
            <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center font-medium text-sm text-gray-600 dark:text-gray-300">
                You
            </div>

            <!-- Creator Input -->
            <div
                class="flex-1 bg-gray-50 dark:bg-gray-900 border border-transparent rounded-3xl px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400 cursor-pointer transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:border-gray-200 dark:hover:border-white/10"
                @click="openModal"
            >
                Chia sẻ code của bạn...
            </div>
        </div>
    </div>

    <!-- Create Post Modal -->
    <CreatePostModal
        :is-open="isModalOpen"
        @close="closeModal"
        @submit="handleSubmit"
    />
</template>


