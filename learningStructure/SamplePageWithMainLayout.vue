<script setup>
import MainLayout from '@/features/testing/layouts/MainLayout.vue';
import SampleNavigationMenu from '@/features/testing/sample/SampleNavigationMenu.vue';
import SampleRecentFiles from '@/features/testing/sample/SampleRecentFiles.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Sample Page With Main Layout" />

    <MainLayout>
        <template #main-sidebar>
            <div class="space-y-4">
                <SampleNavigationMenu active-item="sample" />
                <SampleRecentFiles />
            </div>
        </template>
        
        <template #main-content>
            <div class="p-8">
                <div class="max-w-4xl mx-auto space-y-8">
                    <div>
                        <h1 class="text-3xl font-bold mb-4">Sample Page</h1>
                        <p class="text-muted-foreground text-lg">
                            This page demonstrates the MainLayout with simple content in the main area.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-6 border rounded-lg">
                            <h3 class="font-semibold mb-3">📊 Feature Card 1</h3>
                            <p class="text-sm text-muted-foreground">
                                Description of the first feature. This card shows how content looks in the main layout.
                            </p>
                        </div>
                        
                        <div class="p-6 border rounded-lg">
                            <h3 class="font-semibold mb-3">⚡ Feature Card 2</h3>
                            <p class="text-sm text-muted-foreground">
                                Description of the second feature. Simple layout with just the main sidebar and content.
                            </p>
                        </div>
                        
                        <div class="p-6 border rounded-lg">
                            <h3 class="font-semibold mb-3">🛠️ Feature Card 3</h3>
                            <p class="text-sm text-muted-foreground">
                                Description of the third feature. No complex workspace needed here.
                            </p>
                        </div>
                        
                        <div class="p-6 border rounded-lg">
                            <h3 class="font-semibold mb-3">🎯 Feature Card 4</h3>
                            <p class="text-sm text-muted-foreground">
                                Description of the fourth feature. Clean and simple main content area.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </MainLayout>
</template>