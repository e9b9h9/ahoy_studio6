<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { codemateDashboard } from '@/routes';
import { Head } from '@inertiajs/vue3';
import AddNewCodebook from '@/features/codemate-settings/AddProjectFolder.vue';
import ShowProjectFolders from '@/features/codemate-settings/ShowProjectFolders.vue';

const props = defineProps({
    topLevelFolders: {
        type: Array,
        default: () => []
    }
});

const breadcrumbs = [
    {
        title: 'Codemate Dashboard',
        href: codemateDashboard().url,
    },
];
</script>

<template>
    <Head title="Codemate Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <AddNewCodebook />
        <ShowProjectFolders :folders="topLevelFolders" />
    </AppLayout>
</template>
