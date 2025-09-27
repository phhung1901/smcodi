<script setup lang="ts">
import CodeComparison from './CodeComparison.vue';
import PostActions from './PostActions.vue';

interface Author {
    name: string;
    avatar: string;
    initials: string;
}

interface CodeLine {
    number: number;
    content: string;
}

interface Post {
    id: string;
    title: string;
    description: string;
    author: Author;
    timeAgo: string;
    badCode: CodeLine[];
    goodCode: CodeLine[];
    voteCount: number;
    commentCount: number;
    isVoted?: boolean;
    isSaved?: boolean;
    language?: string;
}

interface Props {
    post: Post;
}

const props = defineProps<Props>();

// Handle post actions
const handleVote = (voted: boolean) => {
    console.log('Vote:', voted);
    // Emit to parent or handle API call
};

const handleComment = () => {
    console.log('Comment clicked');
    // Navigate to post detail or open comment modal
};

const handleSave = (saved: boolean) => {
    console.log('Save:', saved);
    // Handle save/unsave API call
};

const handleShare = () => {
    console.log('Share clicked');
    // Handle share functionality
};
</script>

<template>
    <!-- Post Card -->
    <div class="bg-white dark:bg-black border border-black/8 dark:border-white/10 rounded-xl mb-4 transition-all duration-200 hover:shadow-[0_4px_12px_rgba(0,0,0,0.08)]">
        <!-- Post Header -->
        <div class="p-4 border-b border-black/8 dark:border-white/10">
            <!-- Post Author -->
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center font-medium text-xs text-gray-600 dark:text-gray-300">
                    {{ post.author.initials }}
                </div>
                <div class="flex-1">
                    <div class="font-medium text-black dark:text-white text-sm leading-tight">
                        {{ post.author.name }}
                    </div>
                    <div class="text-gray-500 dark:text-gray-400 text-xs">
                        {{ post.timeAgo }}
                    </div>
                </div>
            </div>

            <!-- Post Title & Description -->
            <h3 class="text-base font-semibold text-black dark:text-white mb-1.5">
                {{ post.title }}
            </h3>
            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                {{ post.description }}
            </p>
        </div>

        <!-- Code Comparison -->
        <CodeComparison
            :bad-code="post.badCode"
            :good-code="post.goodCode"
            :language="post.language"
        />

        <!-- Post Actions -->
        <PostActions
            :vote-count="post.voteCount"
            :comment-count="post.commentCount"
            :is-voted="post.isVoted"
            :is-saved="post.isSaved"
            @vote="handleVote"
            @comment="handleComment"
            @save="handleSave"
            @share="handleShare"
        />
    </div>
</template>


