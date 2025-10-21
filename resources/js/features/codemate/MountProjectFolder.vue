<script setup>
import { PackagePlus } from 'lucide-vue-next';
import { useCodemateStore } from './codemateStore.js';
import { storeToRefs } from 'pinia';

const store = useCodemateStore();
const { topLevelFolders, selectedFolderId } = storeToRefs(store);

</script>

<template>
    <button class="flex items-center justify-center p-2 border border-border rounded hover:bg-muted/50 transition-colors">
        <PackagePlus class="h-4 w-4" />
    </button>
</template>