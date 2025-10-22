<script setup>
import { ref } from 'vue';

const mainSidebarOpen = ref(true);

const toggleMainSidebar = () => {
    mainSidebarOpen.value = !mainSidebarOpen.value;
};
</script>

<template>
    <div class="min-h-screen bg-background flex">
        <!-- Main Left Sidebar -->
        <aside 
            v-if="mainSidebarOpen"
            class="w-64 border-r border-border bg-muted/5 p-4 transition-all duration-300"
        >
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Main Navigation</h2>
                    <button 
                        @click="toggleMainSidebar"
                        class="text-sm text-muted-foreground hover:text-foreground"
                    >
                        ←
                    </button>
                </div>
                
                <slot name="main-sidebar"></slot>
            </div>
        </aside>

        <!-- Toggle button when sidebar is closed -->
        <div 
            v-if="!mainSidebarOpen" 
            class="flex items-start p-2"
        >
            <button 
                @click="toggleMainSidebar"
                class="text-sm text-muted-foreground hover:text-foreground border border-border rounded px-2 py-1"
            >
                →
            </button>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 min-w-0">
            <slot name="main-content"></slot>
        </div>
    </div>
</template>