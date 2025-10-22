<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue';
import AppShell from '@/components/AppShell.vue';
import AppContent from '@/components/AppContent.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const breadcrumbs = [
    { title: 'Home', href: '/' },
    { title: 'Testing', href: '/testing' },
    { title: 'Header Layout Demo', href: '/testing/header-demo' }
];
</script>

<template>
    <AppShell variant="header">
        <AppHeader :breadcrumbs="breadcrumbs" />
        
        <AppContent variant="header" class="p-6">
            <div class="space-y-6">
                <div class="text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        Header Layout Demo
                    </h1>
                    <p class="mt-4 text-lg text-neutral-600 dark:text-neutral-400">
                        This page demonstrates the header layout variant without a sidebar.
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <Card>
                        <CardHeader>
                            <CardTitle>Header Layout</CardTitle>
                            <CardDescription>
                                Uses AppShell with variant="header"
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ul class="space-y-2 text-sm">
                                <li>• Simple top navigation</li>
                                <li>• Full-width content area</li>
                                <li>• No sidebar present</li>
                                <li>• Maximum 7xl container width</li>
                            </ul>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Sidebar Layout</CardTitle>
                            <CardDescription>
                                Uses AppShell with variant="sidebar"
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ul class="space-y-2 text-sm">
                                <li>• Side navigation panel</li>
                                <li>• Content in sidebar inset</li>
                                <li>• Collapsible sidebar</li>
                                <li>• More complex layout</li>
                            </ul>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Key Differences</CardTitle>
                            <CardDescription>
                                Comparing the two layouts
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ul class="space-y-2 text-sm">
                                <li>• Header: Simpler structure</li>
                                <li>• Sidebar: More navigation space</li>
                                <li>• Header: Better for simple apps</li>
                                <li>• Sidebar: Better for complex apps</li>
                            </ul>
                        </CardContent>
                    </Card>
                </div>

                <div class="flex justify-center space-x-4">
                    <Button variant="default">Primary Action</Button>
                    <Button variant="outline">Secondary Action</Button>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Layout Structure</CardTitle>
                        <CardDescription>
                            How this page is structured
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="rounded-lg bg-neutral-100 p-4 font-mono text-sm dark:bg-neutral-800">
                            <div>AppShell variant="header"</div>
                            <div class="ml-4">├── AppHeader (with breadcrumbs)</div>
                            <div class="ml-4">└── AppContent variant="header"</div>
                            <div class="ml-8">└── Your page content (this card)</div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppContent>
    </AppShell>
</template>