<script setup>
import { FolderSync } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import { mount } from '@/routes/codemate';
import { useCodemateStore } from './codemateStore.js';
import { storeToRefs } from 'pinia';

const store = useCodemateStore();
const { selectedFolderId } = storeToRefs(store);

const handleMount = async () => {
    if (!selectedFolderId.value) {
        console.error('No folder selected');
        return;
    }
    
    try {
        const response = await router.post(mount.url(), { 
            folder_id: selectedFolderId.value 
        });
        console.log('Watched files:', response);
    } catch (error) {
        console.error('Mount failed:', error);
    }
};
</script>

<template>
    <button 
        @click="handleMount"
        class="flex items-center justify-center p-2 border border-border rounded hover:bg-muted/50 transition-colors"
    >
        <FolderSync class="h-4 w-4" />
    </button>
</template>