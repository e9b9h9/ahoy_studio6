<script setup>
import { useCodemateStore } from './codemateStore.js';
import { storeToRefs } from 'pinia';
import { watch } from 'vue';
import { useProcessMountedFile } from './useProcessMountedFile.js';

const store = useCodemateStore();
const { selectedFile } = storeToRefs(store);

const { processFile } = useProcessMountedFile();

// Watch for changes to selectedFile and process when it changes
watch(selectedFile, (newFile) => {
    processFile(newFile);
}, { immediate: true });

</script>

<template>
    <div>
        <h1>Process</h1>
        
        <div v-if="selectedFile" class="mt-4">
            <p><strong>File:</strong> {{ selectedFile.name }}</p>
            <p><strong>Init Status:</strong> {{ selectedFile.init_state }}</p>
        </div>
        
        <div v-else class="mt-4 text-muted-foreground">
            No file selected
        </div>
    </div>
</template>