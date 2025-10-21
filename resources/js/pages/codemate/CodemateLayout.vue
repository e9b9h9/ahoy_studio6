<script setup>
import { Button } from '@/components/ui/button';
import { PanelLeft, PanelRight } from 'lucide-vue-next';
import { ref } from 'vue';

const leftSidebarOpen = ref(true);
const rightSidebarOpen = ref(true);

const toggleLeftSidebar = () => {
    leftSidebarOpen.value = !leftSidebarOpen.value;
};

const toggleRightSidebar = () => {
    rightSidebarOpen.value = !rightSidebarOpen.value;
};
</script>

<template>
    <div class="flex h-full flex-col">
			<header class="flex h-14 items-center gap-4 border-b border-border bg-background px-4">
    <Button
        variant="ghost"
        size="icon"
        class="h-8 w-8"
        @click="toggleLeftSidebar"
        :aria-label="leftSidebarOpen ? 'Hide left sidebar' : 'Show left sidebar'"
    >
        <PanelLeft class="h-4 w-4" />
    </Button>
    
    <!-- Header content slot -->
    <div class="flex items-center gap-2">
        <slot name="header-content"></slot>
    </div>
    
    <div class="ml-auto">
        <Button
            variant="ghost"
            size="icon"
            class="h-8 w-8"
            @click="toggleRightSidebar"
            :aria-label="rightSidebarOpen ? 'Hide right sidebar' : 'Show right sidebar'"
        >
            <PanelRight class="h-4 w-4" />
        </Button>
    </div>
</header>

        <!-- Content Area with Sidebars -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Left Sidebar -->
            <aside 
                v-if="leftSidebarOpen"
                class="w-1/4 border-r border-border bg-muted/10 p-4 transition-all duration-300"
            >
                <h3 class="mb-4 text-sm font-semibold text-muted-foreground">Empty</h3>
                <div class="space-y-2">
                    <!-- File changes will go here -->
                    <slot name="left-sidebar">
                        <p class="text-sm text-muted-foreground">Empty</p>
                    </slot>
                </div>
            </aside>

            <!-- Center Content -->
            <main class="flex-1 overflow-auto p-6">
                
                <div class="space-y-4">
                    <!-- Main content area -->
                    <slot name="main-content">
                        <p class="text-muted-foreground">Empty</p>
                    </slot>
                </div>
            </main>

            <!-- Right Sidebar -->
            <aside 
                v-if="rightSidebarOpen"
                class="w-1/4 border-l border-border bg-muted/10 p-4 transition-all duration-300 flex flex-col"
            >
                <h3 class="mb-4 text-sm font-semibold text-muted-foreground">Empty</h3>
                <div class="flex-1 space-y-2">
                 
                    <slot name="right-sidebar-top">
                    </slot>
                </div>
                
  
            </aside>
        </div>

    </div>
</template>