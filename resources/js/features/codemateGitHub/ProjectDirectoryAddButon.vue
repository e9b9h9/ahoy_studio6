<script setup>
import { FolderPlus } from 'lucide-vue-next';
</script>

<template>
    <button 
        @click="handleClick"
        class="flex items-center justify-center p-2 border border-border rounded hover:bg-muted/50 transition-colors"
    >
        <FolderPlus class="h-4 w-4" />
    </button>
</template>