<script setup>
import { useCodemateStore } from './codemateStore.js';
import { storeToRefs } from 'pinia';

const store = useCodemateStore();
const { topLevelFolders, selectedFolderId } = storeToRefs(store);

const handleChange = (event) => {
    const folderId = event.target.value ? parseInt(event.target.value) : null;
    store.setSelectedFolderId(folderId);
};
</script>

<template>
    <div class="px-4">
        <select 
            :value="selectedFolderId" 
            @change="handleChange"
            class="text-sm bg-background border border-border rounded px-2 py-1"
        >
            <option value="">Select Project Folder</option>
            <option 
                v-for="folder in topLevelFolders" 
                :key="folder.id" 
                :value="folder.id"
            >
                {{ folder.name }}
            </option>
        </select>
    </div>
</template>