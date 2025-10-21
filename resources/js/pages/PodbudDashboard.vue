<script setup>
import PodbudMainLayout from '@/layouts/PodbudMainLayout.vue';
import { podbudDashboard } from '@/routes';
import { Head } from '@inertiajs/vue3';

const breadcrumbs = [
    {
        title: 'Podbud Dashboard',
        href: podbudDashboard().url,
    },
];
</script>

<template>
    <Head title="Podbud Dashboard" />

    <PodbudMainLayout :breadcrumbs="breadcrumbs">
        <div class="grid gap-6">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-lg border border-border bg-card p-6">
                    <h3 class="mb-2 text-lg font-medium">Metric 1</h3>
                    <p class="text-2xl font-bold text-primary">123</p>
                </div>
                <div class="rounded-lg border border-border bg-card p-6">
                    <h3 class="mb-2 text-lg font-medium">Metric 2</h3>
                    <p class="text-2xl font-bold text-primary">456</p>
                </div>
                <div class="rounded-lg border border-border bg-card p-6">
                    <h3 class="mb-2 text-lg font-medium">Metric 3</h3>
                    <p class="text-2xl font-bold text-primary">789</p>
                </div>
            </div>
            <div class="rounded-lg border border-border bg-card p-6">
                <h2 class="mb-4 text-xl font-semibold">Main Content Area</h2>
                <p class="text-muted-foreground">This is the main content area of your Podbud dashboard.</p>
            </div>
        </div>
    </PodbudMainLayout>
</template>