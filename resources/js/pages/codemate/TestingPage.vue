<script setup>
import SidebarWithTestingLayout from '@/features/testing/layouts/SidebarWithTestingLayout.vue';
import TestingHeaderTools from '@/features/testing/HeaderTools.vue';
import TestingNavigation from '@/features/testing/NavigationItems.vue';
import TestingMainContent from '@/features/testing/MainContent.vue';
import TestingRightPanel from '@/features/testing/RightPanelContent.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Testing Page" />

    <SidebarWithTestingLayout title="Testing Page">
        <!-- Main sidebar content -->
        <template #main-sidebar>
            <div class="space-y-4">
                <div class="space-y-2">
                    <h4 class="text-xs font-semibold text-muted-foreground uppercase">Navigation</h4>
                    <div class="space-y-1">
                        <p class="text-sm">🏠 Home</p>
                        <p class="text-sm">📊 Dashboard</p>
                        <p class="text-sm">⚙️ Settings</p>
                        <p class="text-sm font-medium text-blue-600">🧪 Testing</p>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <h4 class="text-xs font-semibold text-muted-foreground uppercase">Recent</h4>
                    <div class="space-y-1">
                        <p class="text-xs text-muted-foreground">📄 test-file.vue</p>
                        <p class="text-xs text-muted-foreground">📄 layout.vue</p>
                        <p class="text-xs text-muted-foreground">📄 component.vue</p>
                    </div>
                </div>
            </div>
        </template>
        
        <!-- Header tools -->
        <template #header-content>
            <TestingHeaderTools />
        </template>
        
        <!-- Left sidebar: Navigation -->
        <template #left-sidebar>
            <TestingNavigation />
        </template>
        
        <!-- Main content -->
        <template #main-content>
            <TestingMainContent />
        </template>
        
        <!-- Right sidebar: Tools -->
        <template #right-sidebar>
            <TestingRightPanel />
        </template>
    </SidebarWithTestingLayout>
</template>
