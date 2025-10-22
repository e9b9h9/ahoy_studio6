<script setup>
import MainLayout from '@/features/testing/layouts/MainLayout.vue';
import SampleComponentWithThreeColumnLayout from '@/features/testing/sampleComponents/SampleComponentWithThreeColumnLayout.vue';
import SampleNavigationMenu from '@/features/testing/sample/SampleNavigationMenu.vue';
import SampleRecentFiles from '@/features/testing/sample/SampleRecentFiles.vue';
import SampleToolsList from '@/features/testing/sample/SampleToolsList.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Sample Page With Three Column Layout" />

    <MainLayout>
        <template #main-sidebar>
            <div class="space-y-4">
                <SampleNavigationMenu active-item="workspace" />
                <SampleRecentFiles 
                    title="Recent Workspaces"
                    :files="['workspace-1.vue', 'workspace-2.vue', 'workspace-3.vue']"
                />
                <SampleToolsList />
            </div>
        </template>
        
        <template #main-content>
            <SampleComponentWithThreeColumnLayout />
        </template>
    </MainLayout>
</template>