<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    voteCount: number;
    commentCount: number;
    isVoted?: boolean;
    isSaved?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    isVoted: false,
    isSaved: false
});

// Local reactive state
const voted = ref(props.isVoted);
const saved = ref(props.isSaved);
const currentVoteCount = ref(props.voteCount);

// Emit events
const emit = defineEmits<{
    vote: [voted: boolean];
    comment: [];
    save: [saved: boolean];
    share: [];
}>();

// Handle vote action
const handleVote = () => {
    voted.value = !voted.value;
    currentVoteCount.value += voted.value ? 1 : -1;
    emit('vote', voted.value);
};

// Handle save action
const handleSave = () => {
    saved.value = !saved.value;
    emit('save', saved.value);
};
</script>

<template>
    <!-- Post Actions -->
    <div class="px-4 py-3 flex items-center gap-4 border-t border-black/8 dark:border-white/10">
        <!-- Vote Button -->
        <button
            @click="handleVote"
            :class="[
                'bg-none border-none cursor-pointer transition-all duration-200 flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-[13px]',
                voted
                    ? 'text-black dark:text-white font-medium'
                    : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900 hover:text-black dark:hover:text-white'
            ]"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M7 10l5-5 5 5M12 5v14"/>
            </svg>
            <span class="font-medium text-black dark:text-white">
                {{ currentVoteCount }}
            </span>
        </button>

        <!-- Comment Button -->
        <button
            @click="emit('comment')"
            class="bg-none border-none cursor-pointer transition-all duration-200 flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-[13px] text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900 hover:text-black dark:hover:text-white"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
            </svg>
            <span>{{ commentCount }}</span>
        </button>

        <!-- Save Button -->
        <button
            @click="handleSave"
            :class="[
                'bg-none border-none cursor-pointer transition-all duration-200 flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-[13px]',
                saved
                    ? 'text-black dark:text-white font-medium'
                    : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900 hover:text-black dark:hover:text-white'
            ]"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 3a2.828 2.828 0 114 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
            </svg>
            <span>Lưu</span>
        </button>

        <!-- Share Button -->
        <button
            @click="emit('share')"
            class="bg-none border-none cursor-pointer transition-all duration-200 flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-[13px] text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900 hover:text-black dark:hover:text-white ml-auto"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="18" cy="5" r="3"/>
                <circle cx="6" cy="12" r="3"/>
                <circle cx="18" cy="19" r="3"/>
                <path d="M8.59 13.51l6.83 3.98M15.41 6.51l-6.82 3.98"/>
            </svg>
        </button>
    </div>
</template>


