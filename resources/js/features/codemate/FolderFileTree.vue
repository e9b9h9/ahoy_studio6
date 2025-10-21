<script setup>
import FolderFile from '@/features/codemate/FolderFile.vue';
import { ref, watch, onMounted, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    folderId: {
        type: [Number, null],
        required: true
    },
    parameters: {
        type: Object,
        default: () => ({})
    },
    displayProps: {
        type: Object,
        default: () => ({})
    },
    groupByFolder: {
        type: Boolean,
        default: false
    },
    baseFolderPath: {
        type: String,
        default: ''
    }
});

defineEmits(['fileClick']);

const folderChildren = ref([]);
const isLoading = ref(false);

// Build query string from parameters
const queryString = computed(() => {
    const params = new URLSearchParams(props.parameters);
    return params.toString();
});

const fetchFolderChildren = async () => {
    if (!props.folderId || props.folderId === null) {
        folderChildren.value = [];
        return;
    }
    
    isLoading.value = true;
    try {
        const url = `/codemate/${props.folderId}/request-children${queryString.value ? '?' + queryString.value : ''}`;
        const response = await axios.get(url);
        folderChildren.value = response.data || [];
    } catch (error) {
        console.error('Failed to fetch folder children:', error);
        folderChildren.value = [];
    } finally {
        isLoading.value = false;
    }
};

// Fetch on mount
onMounted(() => {
    fetchFolderChildren();
});

// Re-fetch when folderId or parameters change
watch([() => props.folderId, () => props.parameters], () => {
    fetchFolderChildren();
}, { deep: true });
</script>

<template>
    <div>
        <div v-if="isLoading" class="text-sm text-muted-foreground">
            Loading...
        </div>
        <div v-else-if="!folderId || folderId === null" class="text-sm text-muted-foreground">
            No folder selected
        </div>
        <div v-else-if="folderChildren.length === 0" class="text-sm text-muted-foreground">
            No files found
        </div>
        <FolderFile v-else :folderFiles="folderChildren" :displayProps="displayProps" :groupByFolder="groupByFolder" :baseFolderPath="baseFolderPath" @fileClick="$emit('fileClick', $event)" />
    </div>
</template>