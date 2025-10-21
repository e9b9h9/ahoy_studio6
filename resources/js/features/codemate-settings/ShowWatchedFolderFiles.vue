<script setup>
import WatchFolderFile from './WatchFolderFile.vue';

defineProps({
    folderFiles: {
        type: Array,
        default: () => []
    }
});
</script>

<template>
    <div class="mt-4 space-y-2">
        <div v-for="folderFile in folderFiles" :key="folderFile.id">
            <div class="p-2 ml-4 border border-gray-100 rounded-md bg-gray-25 text-sm flex items-center">
                <WatchFolderFile :folderFile="folderFile" />
                {{ folderFile.name }}
            </div>
            
            <!-- Recursive rendering for folders with children -->
            <ShowWatchedFolderFiles 
                v-if="folderFile.has_children && folderFile.children && folderFile.children.length > 0"
                :folderFiles="folderFile.children" 
            />
        </div>
    </div>
</template>