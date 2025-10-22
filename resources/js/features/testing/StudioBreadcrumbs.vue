<script setup>
const props = defineProps({
    title: {
        type: String,
        default: 'Testing Page'
    }
});
</script>

<template>
    <nav class="border-b border-border bg-background px-6 py-3">
        <div class="flex items-center gap-4">
            <h1 class="text-lg font-semibold">{{ props.title }}</h1>
            <div class="text-sm text-muted-foreground">
         
            </div>
        </div>
    </nav>
</template>