<script setup>
import { computed } from 'vue';

const props = defineProps({
    folderFiles: {
        type: Array,
        default: () => []
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

const emit = defineEmits(['fileClick']);

// Handle file click
const handleFileClick = (file) => {
    emit('fileClick', file);
};

// Get CSS classes based on file init_state
const getFileClasses = (file) => {
    const baseClasses = 'p-1 ml-1 border rounded-md text-sm cursor-pointer transition-colors';
    
    if (file.init_state === 'uninitialized') {
        return `${baseClasses} border-red-200 bg-red-50 hover:bg-red-100`;
    } else {
        return `${baseClasses} border-gray-100 bg-gray-25 hover:bg-gray-50`;
    }
};

// Check if item should be displayed based on displayProps
const shouldDisplayItem = (folderFile) => {
    for (const [key, value] of Object.entries(props.displayProps)) {
        if (folderFile[key] != value) {
            return false;
        }
    }
    return true;
};

// Group files by their parent folder when groupByFolder is enabled
const groupedFiles = computed(() => {
    if (!props.groupByFolder) {
        return { ungrouped: props.folderFiles };
    }
    
    const groups = {};
    
    // Process each folder/file
    props.folderFiles.forEach(folderFile => {
        // If it's a folder, collect its files
        if (folderFile.is_folder && folderFile.children) {
            const displayableFiles = folderFile.children.filter(child => shouldDisplayItem(child));
            if (displayableFiles.length > 0) {
                const fullPath = folderFile.path || folderFile.name;
                const relativePath = props.baseFolderPath ? fullPath.replace(props.baseFolderPath, '').replace(/^\/+/, '') : fullPath;
                groups[relativePath || folderFile.name] = displayableFiles;
            }
            
            // Recursively process nested folders
            const processNested = (items, parentPath = '') => {
                items.forEach(item => {
                    if (item.is_folder && item.children) {
                        const currentPath = parentPath ? `${parentPath}/${item.name}` : item.name;
                        const nestedFiles = item.children.filter(child => shouldDisplayItem(child));
                        if (nestedFiles.length > 0) {
                            const fullPath = item.path || currentPath;
                            const relativePath = props.baseFolderPath ? fullPath.replace(props.baseFolderPath, '').replace(/^\/+/, '') : fullPath;
                            groups[relativePath || item.name] = nestedFiles;
                        }
                        processNested(item.children, currentPath);
                    }
                });
            };
            
            processNested(folderFile.children, folderFile.name);
        }
        // If it's a file at root level, put it in 'Root' group
        else if (!folderFile.is_folder && shouldDisplayItem(folderFile)) {
            if (!groups['Root']) groups['Root'] = [];
            groups['Root'].push(folderFile);
        }
    });
    
    return groups;
});

</script>

<template>
    <!-- Grouped display when groupByFolder is enabled -->
    <div v-if="groupByFolder">
        <div v-for="(files, folderPath) in groupedFiles" :key="folderPath" class="mb-4">
            <h5 class="text-xs font-medium text-muted-foreground mb-2 pl-1">{{ folderPath }}</h5>
            <div v-for="file in files" :key="file.id">
                <div 
                    @click="handleFileClick(file)"
                    :class="getFileClasses(file)"
                    :data-init-state="file.init_state"
                    :data-is-folder="file.is_folder"
                    :data-level="file.level"
                    :data-parent-id="file.parent_id"
                    :data-is-mounted="file.is_mounted"
                    :data-has-children="file.has_children"
                >
                    {{ file.name }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Regular display when groupByFolder is disabled -->
    <div v-else>
        <div v-for="folderFile in folderFiles" :key="folderFile.id">
            <div 
                v-if="shouldDisplayItem(folderFile)"
                @click="handleFileClick(folderFile)"
                :class="getFileClasses(folderFile)"
                :data-init-state="folderFile.init_state"
                :data-is-folder="folderFile.is_folder"
                :data-level="folderFile.level"
                :data-parent-id="folderFile.parent_id"
                :data-is-mounted="folderFile.is_mounted"
                :data-has-children="folderFile.has_children"
            >
                {{ folderFile.name }}
            </div>
            
            <!-- Recursive rendering for folders with children -->
            <FolderFile 
                v-if="folderFile.has_children && folderFile.children && folderFile.children.length > 0"
                :folderFiles="folderFile.children"
                :displayProps="displayProps"
                :groupByFolder="groupByFolder"
                :baseFolderPath="baseFolderPath"
                @fileClick="handleFileClick"
            />
        </div>
    </div>
</template>