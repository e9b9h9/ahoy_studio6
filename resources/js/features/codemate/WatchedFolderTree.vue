<script setup>
import { useCodemateStore } from './codemateStore.js';
import { storeToRefs } from 'pinia';

const store = useCodemateStore();
const { watchedFiles, showWatchedTree } = storeToRefs(store);

const getDashes = (level) => {
    return '-'.repeat(level);
};
</script>

<template>
    <div v-if="showWatchedTree && watchedFiles.length > 0" class="space-y-1">
        <h4 class="text-sm font-semibold text-muted-foreground mb-2">Watched Files</h4>
        <div 
            v-for="file in watchedFiles" 
            :key="file.id"
            class="text-xs font-mono text-muted-foreground"
        >
            {{ getDashes(file.level) }} {{ file.name }}
        </div>
    </div>
    <div v-else-if="showWatchedTree" class="text-sm text-muted-foreground">
        No watched files found
    </div>
</template>