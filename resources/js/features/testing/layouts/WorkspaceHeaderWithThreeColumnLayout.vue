<script setup>
import WorkspaceHeader from '@/features/testing/WorkspaceHeaderContent.vue';
import ThreeColumnLayout from '@/features/testing/layouts/ThreeColumnLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: 'Testing Page'
    }
});

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
                <slot name="header-content"></slot>
            </template>
        </WorkspaceHeader>
        
        <ThreeColumnLayout 
            :left-sidebar-open="leftSidebarOpen"
            :right-sidebar-open="rightSidebarOpen"
        >
            <template #left-sidebar>
                <slot name="left-sidebar"></slot>
            </template>
            
            <template #main-content>
                <slot name="main-content"></slot>
            </template>
            
            <template #right-sidebar>
                <slot name="right-sidebar"></slot>
            </template>
        </ThreeColumnLayout>
    </div>
</template>