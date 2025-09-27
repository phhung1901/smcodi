<script setup lang="ts">
// Layout dành riêng cho giao diện CodeCraft
</script>

<template>
    <div class="h-screen overflow-y-auto bg-white dark:bg-black">
        <!-- Main Content Container -->
        <main class="pt-16">
            <div class="max-w-[680px] mx-auto px-5">
                <slot />
            </div>
        </main>
    </div>
</template>


