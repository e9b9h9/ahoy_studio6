<script setup>
const props = defineProps({
    leftSidebarOpen: {
        type: Boolean,
        default: true
    },
    rightSidebarOpen: {
        type: Boolean,
        default: true
    }
});
</script>

<template>
    <!-- Three-column layout -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Left Sidebar -->
        <aside 
            v-if="leftSidebarOpen"
            class="w-1/4 border-r border-border bg-muted/10 p-4 transition-all duration-300"
        >
            <slot name="left-sidebar"></slot>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto p-6">
            <slot name="main-content"></slot>
        </main>

        <!-- Right Sidebar -->
        <aside 
            v-if="rightSidebarOpen"
            class="w-1/4 border-l border-border bg-muted/10 p-4 transition-all duration-300 flex flex-col"
        >
            <slot name="right-sidebar"></slot>
        </aside>
    </div>
</template>