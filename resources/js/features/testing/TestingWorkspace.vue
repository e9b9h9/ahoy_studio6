<script setup>
import WorkspaceHeaderWithThreeColumnLayout from '@/features/testing/layouts/WorkspaceHeaderWithThreeColumnLayout.vue';

const props = defineProps({
    title: {
        type: String,
        default: ''
    }
});
</script>

<template>
    <WorkspaceHeaderWithThreeColumnLayout :title="props.title">
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
    </WorkspaceHeaderWithThreeColumnLayout>
</template>