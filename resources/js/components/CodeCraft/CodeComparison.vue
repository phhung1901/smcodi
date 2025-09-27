<script setup lang="ts">
interface CodeLine {
    number: number;
    content: string;
}

interface Props {
    badCode: CodeLine[];
    goodCode: CodeLine[];
    language?: string;
}

const props = withDefaults(defineProps<Props>(), {
    language: 'javascript'
});
</script>

<template>
    <!-- Code Comparison -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-px bg-black/8 dark:bg-white/10">
        <!-- Bad Code Panel -->
        <div class="bg-white dark:bg-black relative">
            <span class="absolute top-3 left-4 px-2 py-1 rounded bg-black dark:bg-white text-white dark:text-black text-[11px] font-semibold uppercase tracking-wider z-10 opacity-80">
                Bad Code
            </span>

            <div class="pt-11 pb-4 font-mono text-xs leading-relaxed bg-[#1a1a1a] overflow-x-auto">
                <div class="table w-full">
                    <!-- Line Numbers -->
                    <div class="table-cell w-px px-4 text-right select-none opacity-30 text-[#e5e5e5] align-top">
                        <div v-for="line in badCode" :key="line.number" class="text-[11px] leading-relaxed">
                            {{ line.number }}
                        </div>
                    </div>

                    <!-- Code Lines -->
                    <div class="table-cell px-4 pl-2 text-[#e5e5e5]">
                        <div v-for="line in badCode" :key="line.number" class="leading-relaxed" v-html="line.content">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Good Code Panel -->
        <div class="bg-white dark:bg-black relative">
            <span class="absolute top-3 left-4 px-2 py-1 rounded bg-black dark:bg-white text-white dark:text-black text-[11px] font-semibold uppercase tracking-wider z-10">
                Good Code
            </span>

            <div class="pt-11 pb-4 font-mono text-xs leading-relaxed bg-[#1a1a1a] overflow-x-auto">
                <div class="table w-full">
                    <!-- Line Numbers -->
                    <div class="table-cell w-px px-4 text-right select-none opacity-30 text-[#e5e5e5] align-top">
                        <div v-for="line in goodCode" :key="line.number" class="text-[11px] leading-relaxed">
                            {{ line.number }}
                        </div>
                    </div>

                    <!-- Code Lines -->
                    <div class="table-cell px-4 pl-2 text-[#e5e5e5]">
                        <div v-for="line in goodCode" :key="line.number" class="leading-relaxed" v-html="line.content">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Syntax Highlighting Colors */
:deep(.keyword) { color: #c678dd; }
:deep(.function) { color: #61afef; }
:deep(.string) { color: #98c379; }
:deep(.comment) { color: #5c6370; font-style: italic; }
:deep(.variable) { color: #e06c75; }
:deep(.operator) { color: #56b6c2; }
:deep(.number) { color: #d19a66; }
:deep(.class-name) { color: #e5c07b; }
:deep(.punctuation) { color: #abb2bf; }
:deep(.property) { color: #d19a66; }
:deep(.method) { color: #61afef; }

/* Dark mode support for code background */
@media (prefers-color-scheme: dark) {
    .bg-\[\#1a1a1a\] {
        background: #0a0a0a;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .grid-cols-1.lg\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}
</style>
