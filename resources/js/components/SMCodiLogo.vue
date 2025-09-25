<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
    variant?: 'white' | 'black' | 'auto';
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'auto',
});

const logoSrc = props.variant === 'white'
    ? '/smcodi-logo-white.png'
    : props.variant === 'black'
    ? '/smcodi-logo-black.png'
    : '/smcodi-logo-white.png'; // default for auto
</script>

<template>
    <div v-if="variant === 'auto'" class="relative">
        <!-- Logo for light mode -->
        <img
            src="/smcodi-logo-black.png"
            alt="SMCodi Logo"
            :class="className"
            class="block dark:hidden"
            v-bind="$attrs"
        />
        <!-- Logo for dark mode -->
        <img
            src="/smcodi-logo-white.png"
            alt="SMCodi Logo"
            :class="className"
            class="hidden dark:block"
            v-bind="$attrs"
        />
    </div>
    <img
        v-else
        :src="logoSrc"
        alt="SMCodi Logo"
        :class="className"
        v-bind="$attrs"
    />
</template>
