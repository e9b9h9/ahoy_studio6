<script setup>
const props = defineProps({
    tools: {
        type: Array,
        default: () => [
            { icon: '🔧', name: 'Tool Manager' },
            { icon: '📋', name: 'Project Settings' },
            { icon: '🎯', name: 'Quick Actions' }
        ]
    },
    title: {
        type: String,
        default: 'Tools'
    }
});
</script>

<template>
    <div class="space-y-2">
        <h4 class="text-xs font-semibold text-muted-foreground uppercase">{{ title }}</h4>
        <div class="space-y-1">
            <p 
                v-for="tool in tools" 
                :key="tool.name"
                class="text-xs text-muted-foreground"
            >
                {{ tool.icon }} {{ tool.name }}
            </p>
        </div>
    </div>
</template>