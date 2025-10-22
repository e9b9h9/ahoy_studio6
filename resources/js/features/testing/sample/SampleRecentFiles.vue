<script setup>
const props = defineProps({
    files: {
        type: Array,
        default: () => [
            'sample-1.vue',
            'sample-2.vue', 
            'sample-3.vue'
        ]
    },
    title: {
        type: String,
        default: 'Recent'
    }
});
</script>

<template>
    <div class="space-y-2">
        <h4 class="text-xs font-semibold text-muted-foreground uppercase">{{ title }}</h4>
        <div class="space-y-1">
            <p 
                v-for="file in files" 
                :key="file"
                class="text-xs text-muted-foreground"
            >
                📄 {{ file }}
            </p>
        </div>
    </div>
</template>