<script setup>
import FolderFileTree from '@/features/codemate/FolderFileTree.vue';
import { useCodemateStore } from './codemateStore.js';
import { storeToRefs } from 'pinia';

const store = useCodemateStore();
const { selectedFolder, selectedFolderId } = storeToRefs(store);

// Parameters to pass to FolderFileTree
const filterParameters = {
    is_mounted: '1'
};

// Display props to only show files (not folders)
const displayProps = {
    is_folder: false
};

// Handle file clicks
const handleFileClick = (file) => {
    store.setSelectedFile(file);
};

</script>

<template>
    <div class="mounted-files-section">
        <h4 class="text-sm font-semibold text-muted-foreground mb-2">Mounted Files</h4>
      
        <FolderFileTree :folderId="selectedFolderId" :parameters="filterParameters" :displayProps="displayProps" :groupByFolder="true" :baseFolderPath="selectedFolder?.path" @fileClick="handleFileClick" />
    </div>

</template>