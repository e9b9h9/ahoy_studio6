<script setup>
import StudioBreadcrumbs from '@/features/testing/StudioBreadcrumbs.vue';
import TestingWorkspace from '@/features/testing/TestingWorkspace.vue';
import { ref } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: 'Testing Page'
    }
});

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
                
                <slot name="main-sidebar">
                    <div class="space-y-2">
                        <h4 class="text-xs font-semibold text-muted-foreground uppercase">Menu</h4>
                        <div class="space-y-1">
                            <p class="text-sm">🏠 Home</p>
                            <p class="text-sm">📊 Dashboard</p>
                            <p class="text-sm">⚙️ Settings</p>
                            <p class="text-sm">🧪 Testing</p>
                        </div>
                    </div>
                </slot>
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
        <div class="flex-1 min-w-0 flex flex-col">
            <StudioBreadcrumbs :title="props.title" />
            
            <div class="flex-1">
                <TestingWorkspace :title="props.title">
                    <template #header-content>
                        <slot name="header-content"></slot>
                    </template>
                    
                    <template #left-sidebar>
                        <slot name="left-sidebar"></slot>
                    </template>
                    
                    <template #main-content>
                        <slot name="main-content"></slot>
                    </template>
                    
                    <template #right-sidebar>
                        <slot name="right-sidebar"></slot>
                    </template>
                </TestingWorkspace>
            </div>
        </div>
    </div>
</template>