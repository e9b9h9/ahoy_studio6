<script setup>
import { FolderTree } from 'lucide-vue-next';
import { useCodemateStore } from './codemateStore.js';

const store = useCodemateStore();

const handleClick = async () => {
    if (typeof store.toggleWatchedTree === 'function') {
        await store.toggleWatchedTree();
    } else {
        console.error('toggleWatchedTree is not a function', store);
    }
};
</script>

<template>
    <button 
        @click="handleClick"
        class="flex items-center justify-center p-2 border border-border rounded hover:bg-muted/50 transition-colors"
    >
        <FolderTree class="h-4 w-4" />
    </button>
</template>