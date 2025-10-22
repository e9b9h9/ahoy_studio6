<script setup>
const props = defineProps({
    activeItem: {
        type: String,
        default: ''
    }
});
</script>

<template>
    <div class="space-y-2">
        <h4 class="text-xs font-semibold text-muted-foreground uppercase">Navigation</h4>
        <div class="space-y-1">
            <p class="text-sm" :class="{ 'font-medium text-blue-600': activeItem === 'home' }">🏠 Home</p>
            <p class="text-sm" :class="{ 'font-medium text-blue-600': activeItem === 'dashboard' }">📊 Dashboard</p>
            <p class="text-sm" :class="{ 'font-medium text-blue-600': activeItem === 'settings' }">⚙️ Settings</p>
            <p class="text-sm" :class="{ 'font-medium text-blue-600': activeItem === 'sample' }">📝 Sample Page</p>
            <p class="text-sm" :class="{ 'font-medium text-blue-600': activeItem === 'workspace' }">🏗️ Workspace</p>
        </div>
    </div>
</template>