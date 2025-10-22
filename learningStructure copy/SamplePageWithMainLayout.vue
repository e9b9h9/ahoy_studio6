<script setup>
import MainLayout from '@/features/testing/layouts/MainLayout.vue';
import DefaultSidebarContent from '@/features/testing/DefaultSidebarContent.vue';
import MainContent from '@/features/testing/MainContent.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Main Layout" />

    <MainLayout>
        <template #main-sidebar>
                <DefaultSidebarContent/>
          
           
        </template>
        
        <template #main-content>
                <MainContent/>
        </template>
    </MainLayout>
</template>