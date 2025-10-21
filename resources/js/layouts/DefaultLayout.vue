<script setup lang="ts">
import SidebarLayout from '@/layouts/SidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <SidebarLayout :breadcrumbs="breadcrumbs">
        <slot />
    </SidebarLayout>
</template>