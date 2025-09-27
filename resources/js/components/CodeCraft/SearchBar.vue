<script setup lang="ts">
import { ref } from 'vue';

// Reactive search value
const searchValue = ref('');

// Emit events for parent component
const emit = defineEmits<{
    search: [value: string];
}>();

// Handle search input
const handleSearch = () => {
    emit('search', searchValue.value);
};
</script>

<template>
    <!-- Search Bar -->
    <div class="relative mb-6">
        <!-- Search Icon -->
        <svg
            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
        >
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
        </svg>

        <!-- Search Input -->
        <input
            v-model="searchValue"
            @input="handleSearch"
            type="text"
            class="w-full pl-11 pr-4 py-3 border border-gray-200 dark:border-white/10 rounded-xl text-sm bg-white dark:bg-black text-gray-900 dark:text-white transition-all duration-200 focus:outline-none focus:border-gray-400 focus:shadow-[0_0_0_4px_rgba(0,0,0,0.04)]"
            placeholder="Tìm kiếm code, bài viết, tác giả..."
        >
    </div>
</template>


