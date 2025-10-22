<script setup>
import WorkspaceHeader from '@/features/testing/WorkspaceHeaderContent.vue';
import ThreeColumnContent from '@/features/testing/layouts/ThreeColumnLayout.vue';
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
    <div class="h-full bg-background flex flex-col">
        <WorkspaceHeader 
            :left-sidebar-open="leftSidebarOpen"
            :right-sidebar-open="rightSidebarOpen"
            @toggle-left-sidebar="toggleLeftSidebar"
            @toggle-right-sidebar="toggleRightSidebar"
        >
            <template #header-content>
                <span class="text-sm font-medium">Sample Tools</span>
                <span class="text-xs text-muted-foreground">Ready</span>
            </template>
        </WorkspaceHeader>
        
        <ThreeColumnContent 
            :left-sidebar-open="leftSidebarOpen"
            :right-sidebar-open="rightSidebarOpen"
        >
            <template #left-sidebar>
                <div class="space-y-2">
                    <h4 class="text-xs font-semibold text-muted-foreground uppercase">Navigation</h4>
                    <div class="space-y-1">
                        <p class="text-sm">📁 Sample Folder 1</p>
                        <p class="text-sm">📁 Sample Folder 2</p>
                        <p class="text-sm">📄 Sample File 1</p>
                        <p class="text-sm">📄 Sample File 2</p>
                    </div>
                </div>
            </template>
            
            <template #main-content>
                <div class="space-y-6">
                    <div>
                        <h1 class="text-2xl font-bold mb-4">Sample Component</h1>
                        <p class="text-muted-foreground">
                            This is a sample component using the three-column layout.
                        </p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold mb-2">📊 Sample Section 1</h3>
                            <p class="text-sm text-muted-foreground">Content for sample section 1</p>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">⚡ Sample Section 2</h3>
                            <p class="text-sm text-muted-foreground">Content for sample section 2</p>
                        </div>
                    </div>
                </div>
            </template>
            
            <template #right-sidebar>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-xs font-semibold text-muted-foreground uppercase mb-2">Tools</h4>
                        <div class="space-y-2">
                            <p class="text-sm">🔧 Sample Tool 1</p>
                            <p class="text-sm">⚙️ Sample Tool 2</p>
                            <p class="text-sm">🛠️ Sample Tool 3</p>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-xs font-semibold text-muted-foreground uppercase mb-2">Status</h4>
                        <div class="text-sm space-y-1">
                            <p>Status: <span class="text-green-600">✅ Active</span></p>
                            <p>Items: <span class="font-mono">12</span></p>
                            <p>Mode: <span class="text-blue-600">Sample</span></p>
                        </div>
                    </div>
                </div>
            </template>
        </ThreeColumnContent>
    </div>
</template>