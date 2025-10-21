<script setup>
import ShowWatchedFolderFiles from './ShowWatchedFolderFiles.vue';

defineProps({
    folders: {
        type: Array,
        default: () => []
    }
});
</script>

<template>
    <div class="mt-4 space-y-2">
        <div 
            v-for="folder in folders" 
            :key="folder.id"
            class="p-3 border border-gray-200 rounded-md bg-gray-50"
        >
            {{ folder.path }}
            <ShowWatchedFolderFiles :folderFiles="folder.children" />
        </div>
    </div>
</template>