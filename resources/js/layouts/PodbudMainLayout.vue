<script setup>
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';

defineProps({
    breadcrumbs: {
        type: Array,
        default: () => [],
    }
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>