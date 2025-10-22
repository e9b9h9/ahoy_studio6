# Folder Structure Examples with JavaScript Files

This document provides comprehensive folder structure examples incorporating JavaScript/TypeScript files, stores, composables, utilities, and functions alongside Vue components. Each structure shows how to organize different types of files and their relationships.

---

## Current Project JS Files Analysis

**Existing JavaScript Files:**
- `features/codemate/codemateStore.js` - Pinia store for codemate state management
- `features/codemate/useProcessMountedFile.js` - Composable for file processing logic

---

## 1. Atomic Design + JavaScript Integration

```
resources/js/
├── atoms/                    # Basic building blocks
│   ├── Button.vue
│   ├── Card.vue
│   ├── Input.vue
│   └── index.js             # Export all atoms
├── molecules/               # Simple component groups
│   ├── HeaderTools.vue
│   ├── NavigationMenu.vue
│   ├── FileList.vue
│   └── index.js            # Export all molecules
├── organisms/              # Complex assemblies
│   ├── TestingWorkspace.vue
│   ├── ProjectSidebar.vue
│   ├── FileExplorer.vue
│   └── index.js           # Export all organisms
├── templates/             # Layout templates
│   ├── MainLayout.vue
│   ├── SidebarLayout.vue
│   └── index.js          # Export all templates
├── pages/                # Complete pages
│   ├── TestingPage.vue
│   ├── CodematePage.vue
│   └── index.js         # Export all pages
├── stores/              # State management
│   ├── codemateStore.js
│   ├── userStore.js
│   ├── uiStore.js
│   └── index.js        # Export all stores
├── composables/        # Reusable logic
│   ├── useFileProcessing.js
│   ├── useProjectFolder.js
│   ├── useLayoutState.js
│   └── index.js       # Export all composables
├── utils/             # Pure utility functions
│   ├── fileHelpers.js
│   ├── formatters.js
│   ├── validators.js
│   └── index.js      # Export all utilities
└── types/            # TypeScript definitions
    ├── codemate.ts
    ├── ui.ts
    └── index.ts
```

### Example Files:

**atoms/index.js**
```javascript
export { default as Button } from './Button.vue'
export { default as Card } from './Card.vue'
export { default as Input } from './Input.vue'
```

**composables/useFileProcessing.js**
```javascript
import { ref } from 'vue'
import axios from 'axios'

export function useFileProcessing() {
    const isProcessing = ref(false)
    const processedFiles = ref([])

    const processFile = async (file) => {
        isProcessing.value = true
        try {
            const response = await axios.post('/api/process-file', { file_id: file.id })
            processedFiles.value.push(response.data)
            return response.data
        } catch (error) {
            console.error('File processing failed:', error)
            throw error
        } finally {
            isProcessing.value = false
        }
    }

    return {
        isProcessing,
        processedFiles,
        processFile
    }
}
```

**utils/fileHelpers.js**
```javascript
export const formatFileSize = (bytes) => {
    const sizes = ['Bytes', 'KB', 'MB', 'GB']
    if (bytes === 0) return '0 Bytes'
    const i = Math.floor(Math.log(bytes) / Math.log(1024))
    return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i]
}

export const getFileExtension = (filename) => {
    return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2)
}

export const isImageFile = (filename) => {
    const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']
    return imageExts.includes(getFileExtension(filename).toLowerCase())
}
```

---

## 2. Feature-First with JavaScript Integration

```
resources/js/
├── shared/                    # Cross-feature components
│   ├── components/
│   │   ├── ui/
│   │   │   ├── Button.vue
│   │   │   ├── Card.vue
│   │   │   └── index.js
│   │   └── layout/
│   │       ├── MainLayout.vue
│   │       ├── ThreeColumnLayout.vue
│   │       └── index.js
│   ├── composables/
│   │   ├── useApi.js
│   │   ├── useLocalStorage.js
│   │   └── index.js
│   ├── utils/
│   │   ├── formatters.js
│   │   ├── validators.js
│   │   └── index.js
│   └── stores/
│       ├── appStore.js
│       ├── userStore.js
│       └── index.js
├── features/
│   ├── testing/
│   │   ├── components/
│   │   │   ├── HeaderTools.vue
│   │   │   ├── NavigationItems.vue
│   │   │   └── index.js
│   │   ├── composables/
│   │   │   ├── useTestingWorkspace.js
│   │   │   ├── useLayoutState.js
│   │   │   └── index.js
│   │   ├── stores/
│   │   │   ├── testingStore.js
│   │   │   └── index.js
│   │   ├── utils/
│   │   │   ├── testingHelpers.js
│   │   │   └── index.js
│   │   └── pages/
│   │       ├── TestingPage.vue
│   │       └── index.js
│   ├── codemate/
│   │   ├── components/
│   │   │   ├── ProjectFolderDropdown.vue
│   │   │   ├── FileTree.vue
│   │   │   └── index.js
│   │   ├── composables/
│   │   │   ├── useFileProcessing.js
│   │   │   ├── useProjectFolder.js
│   │   │   └── index.js
│   │   ├── stores/
│   │   │   ├── codemateStore.js
│   │   │   └── index.js
│   │   ├── utils/
│   │   │   ├── fileHelpers.js
│   │   │   ├── treeHelpers.js
│   │   │   └── index.js
│   │   └── pages/
│   │       ├── CodematePage.vue
│   │       └── index.js
│   └── settings/
│       ├── components/
│       ├── composables/
│       ├── stores/
│       └── pages/
└── pages/                    # Top-level pages only
    ├── Dashboard.vue
    ├── Welcome.vue
    └── index.js
```

### Example Files:

**features/codemate/composables/useProjectFolder.js**
```javascript
import { ref, computed } from 'vue'
import { useCodemateStore } from '../stores/codemateStore.js'
import { storeToRefs } from 'pinia'

export function useProjectFolder() {
    const store = useCodemateStore()
    const { topLevelFolders, selectedFolderId } = storeToRefs(store)
    
    const selectedFolder = computed(() => {
        return topLevelFolders.value.find(folder => folder.id === selectedFolderId.value)
    })
    
    const selectFolder = (folderId) => {
        store.setSelectedFolderId(folderId)
    }
    
    const loadFolders = async () => {
        await store.fetchTopLevelFolders()
    }
    
    return {
        topLevelFolders,
        selectedFolderId,
        selectedFolder,
        selectFolder,
        loadFolders
    }
}
```

**features/codemate/utils/treeHelpers.js**
```javascript
export const buildFileTree = (files, basePath = '') => {
    const tree = {}
    
    files.forEach(file => {
        const path = file.path.replace(basePath, '').split('/')
        let current = tree
        
        path.forEach((segment, index) => {
            if (!current[segment]) {
                current[segment] = index === path.length - 1 
                    ? { ...file, isFile: true }
                    : { children: {}, isFile: false }
            }
            current = current[segment].children || current[segment]
        })
    })
    
    return tree
}

export const flattenTree = (tree, path = '') => {
    const result = []
    
    Object.entries(tree).forEach(([key, value]) => {
        const currentPath = path ? `${path}/${key}` : key
        
        if (value.isFile) {
            result.push({ ...value, path: currentPath })
        } else {
            result.push(...flattenTree(value.children, currentPath))
        }
    })
    
    return result
}
```

---

## 3. Complexity-Based with JavaScript Integration

```
resources/js/
├── primitives/              # Basic elements
│   ├── components/
│   │   ├── Button.vue
│   │   ├── Input.vue
│   │   └── index.js
│   ├── utils/
│   │   ├── dom.js
│   │   ├── events.js
│   │   └── index.js
│   └── constants/
│       ├── ui.js
│       ├── colors.js
│       └── index.js
├── components/             # Single-purpose components
│   ├── content/
│   │   ├── HeaderTools.vue
│   │   ├── NavigationItems.vue
│   │   └── index.js
│   ├── forms/
│   │   ├── ProjectForm.vue
│   │   ├── FileUpload.vue
│   │   └── index.js
│   └── display/
│       ├── FileList.vue
│       ├── TreeView.vue
│       └── index.js
├── layouts/               # Layout containers
│   ├── components/
│   │   ├── MainLayout.vue
│   │   ├── ThreeColumnLayout.vue
│   │   └── index.js
│   ├── composables/
│   │   ├── useLayoutState.js
│   │   ├── useResponsive.js
│   │   └── index.js
│   └── utils/
│       ├── layoutHelpers.js
│       └── index.js
├── composites/           # Multi-component assemblies
│   ├── components/
│   │   ├── TestingWorkspace.vue
│   │   ├── ProjectExplorer.vue
│   │   └── index.js
│   ├── composables/
│   │   ├── useWorkspace.js
│   │   ├── useFileManagement.js
│   │   └── index.js
│   └── stores/
│       ├── workspaceStore.js
│       ├── explorerStore.js
│       └── index.js
├── services/             # Business logic & API
│   ├── api/
│   │   ├── codemateApi.js
│   │   ├── fileApi.js
│   │   └── index.js
│   ├── business/
│   │   ├── fileProcessor.js
│   │   ├── projectManager.js
│   │   └── index.js
│   └── utils/
│       ├── apiHelpers.js
│       ├── errorHandling.js
│       └── index.js
└── pages/               # Complete applications
    ├── TestingPage.vue
    ├── CodematePage.vue
    └── index.js
```

### Example Files:

**services/business/fileProcessor.js**
```javascript
import { fileApi } from '../api/fileApi.js'
import { formatFileSize } from '../../primitives/utils/index.js'

export class FileProcessor {
    constructor() {
        this.processingQueue = []
        this.isProcessing = false
    }
    
    async processFile(file) {
        return new Promise((resolve, reject) => {
            this.processingQueue.push({ file, resolve, reject })
            this.processNext()
        })
    }
    
    async processNext() {
        if (this.isProcessing || this.processingQueue.length === 0) return
        
        this.isProcessing = true
        const { file, resolve, reject } = this.processingQueue.shift()
        
        try {
            const result = await this.doProcess(file)
            resolve(result)
        } catch (error) {
            reject(error)
        } finally {
            this.isProcessing = false
            this.processNext()
        }
    }
    
    async doProcess(file) {
        const metadata = {
            size: formatFileSize(file.size),
            type: file.type,
            lastModified: new Date(file.lastModified)
        }
        
        const response = await fileApi.initialize(file.id, metadata)
        return response.data
    }
}

export const fileProcessor = new FileProcessor()
```

**layouts/composables/useLayoutState.js**
```javascript
import { ref, computed } from 'vue'
import { useLocalStorage } from '@vueuse/core'

export function useLayoutState(layoutId) {
    const leftSidebarOpen = useLocalStorage(`${layoutId}-left-sidebar`, true)
    const rightSidebarOpen = useLocalStorage(`${layoutId}-right-sidebar`, true)
    const mainSidebarOpen = useLocalStorage(`${layoutId}-main-sidebar`, true)
    
    const layoutClasses = computed(() => ({
        'has-left-sidebar': leftSidebarOpen.value,
        'has-right-sidebar': rightSidebarOpen.value,
        'has-main-sidebar': mainSidebarOpen.value
    }))
    
    const toggleLeftSidebar = () => {
        leftSidebarOpen.value = !leftSidebarOpen.value
    }
    
    const toggleRightSidebar = () => {
        rightSidebarOpen.value = !rightSidebarOpen.value
    }
    
    const toggleMainSidebar = () => {
        mainSidebarOpen.value = !mainSidebarOpen.value
    }
    
    return {
        leftSidebarOpen,
        rightSidebarOpen,
        mainSidebarOpen,
        layoutClasses,
        toggleLeftSidebar,
        toggleRightSidebar,
        toggleMainSidebar
    }
}
```

---

## 4. Domain + Type Hybrid with JavaScript

```
resources/js/
├── core/                     # Core application logic
│   ├── stores/
│   │   ├── appStore.js
│   │   ├── userStore.js
│   │   └── index.js
│   ├── services/
│   │   ├── apiService.js
│   │   ├── authService.js
│   │   └── index.js
│   ├── utils/
│   │   ├── common.js
│   │   ├── validators.js
│   │   └── index.js
│   └── types/
│       ├── api.ts
│       ├── user.ts
│       └── index.ts
├── ui/                      # Shared UI system
│   ├── components/
│   │   ├── atoms/
│   │   │   ├── Button.vue
│   │   │   └── index.js
│   │   ├── molecules/
│   │   │   ├── SearchBox.vue
│   │   │   └── index.js
│   │   └── index.js
│   ├── composables/
│   │   ├── useTheme.js
│   │   ├── useModal.js
│   │   └── index.js
│   ├── utils/
│   │   ├── styling.js
│   │   ├── animations.js
│   │   └── index.js
│   └── constants/
│       ├── themes.js
│       ├── breakpoints.js
│       └── index.js
├── domains/                # Business domains
│   ├── codemate/
│   │   ├── components/
│   │   │   ├── atoms/
│   │   │   │   ├── FileIcon.vue
│   │   │   │   └── index.js
│   │   │   ├── molecules/
│   │   │   │   ├── FolderItem.vue
│   │   │   │   └── index.js
│   │   │   ├── organisms/
│   │   │   │   ├── FileExplorer.vue
│   │   │   │   └── index.js
│   │   │   └── index.js
│   │   ├── stores/
│   │   │   ├── codemateStore.js
│   │   │   ├── filesStore.js
│   │   │   └── index.js
│   │   ├── services/
│   │   │   ├── fileService.js
│   │   │   ├── projectService.js
│   │   │   └── index.js
│   │   ├── composables/
│   │   │   ├── useFileManagement.js
│   │   │   ├── useProjectSync.js
│   │   │   └── index.js
│   │   ├── utils/
│   │   │   ├── fileHelpers.js
│   │   │   ├── pathUtils.js
│   │   │   └── index.js
│   │   └── types/
│   │       ├── file.ts
│   │       ├── project.ts
│   │       └── index.ts
│   ├── testing/
│   │   ├── components/
│   │   │   ├── molecules/
│   │   │   │   ├── TestResult.vue
│   │   │   │   └── index.js
│   │   │   ├── organisms/
│   │   │   │   ├── TestingWorkspace.vue
│   │   │   │   └── index.js
│   │   │   └── index.js
│   │   ├── stores/
│   │   │   ├── testingStore.js
│   │   │   └── index.js
│   │   ├── services/
│   │   │   ├── testRunner.js
│   │   │   └── index.js
│   │   ├── composables/
│   │   │   ├── useTestExecution.js
│   │   │   └── index.js
│   │   └── utils/
│   │       ├── testHelpers.js
│   │       └── index.js
│   └── settings/
│       ├── components/
│       ├── stores/
│       ├── services/
│       └── composables/
└── app/                    # Application assembly
    ├── layouts/
    │   ├── AppLayout.vue
    │   ├── AuthLayout.vue
    │   └── index.js
    ├── pages/
    │   ├── Dashboard.vue
    │   ├── Settings.vue
    │   └── index.js
    └── router/
        ├── routes.js
        ├── guards.js
        └── index.js
```

### Example Files:

**domains/codemate/services/fileService.js**
```javascript
import { apiService } from '../../../core/services/apiService.js'
import { fileProcessor } from './fileProcessor.js'

export class FileService {
    async getProjectFiles(projectId) {
        const response = await apiService.get(`/projects/${projectId}/files`)
        return response.data
    }
    
    async uploadFile(file, projectId) {
        const formData = new FormData()
        formData.append('file', file)
        formData.append('project_id', projectId)
        
        const response = await apiService.post('/files/upload', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        
        // Automatically process uploaded file
        await fileProcessor.processFile(response.data)
        
        return response.data
    }
    
    async deleteFile(fileId) {
        await apiService.delete(`/files/${fileId}`)
    }
    
    async getFileContent(fileId) {
        const response = await apiService.get(`/files/${fileId}/content`)
        return response.data
    }
}

export const fileService = new FileService()
```

**domains/codemate/composables/useFileManagement.js**
```javascript
import { ref, computed } from 'vue'
import { useCodemateStore } from '../stores/codemateStore.js'
import { fileService } from '../services/fileService.js'
import { useNotifications } from '../../../ui/composables/useNotifications.js'

export function useFileManagement() {
    const store = useCodemateStore()
    const { showSuccess, showError } = useNotifications()
    
    const isLoading = ref(false)
    const selectedFiles = ref([])
    
    const hasSelection = computed(() => selectedFiles.value.length > 0)
    
    const loadFiles = async (projectId) => {
        isLoading.value = true
        try {
            const files = await fileService.getProjectFiles(projectId)
            store.setFiles(files)
        } catch (error) {
            showError('Failed to load files')
            console.error('Load files error:', error)
        } finally {
            isLoading.value = false
        }
    }
    
    const uploadFiles = async (files, projectId) => {
        for (const file of files) {
            try {
                await fileService.uploadFile(file, projectId)
                showSuccess(`${file.name} uploaded successfully`)
            } catch (error) {
                showError(`Failed to upload ${file.name}`)
            }
        }
        await loadFiles(projectId)
    }
    
    const deleteSelectedFiles = async () => {
        for (const fileId of selectedFiles.value) {
            try {
                await fileService.deleteFile(fileId)
            } catch (error) {
                showError('Failed to delete some files')
            }
        }
        selectedFiles.value = []
        await loadFiles(store.currentProjectId)
    }
    
    return {
        isLoading,
        selectedFiles,
        hasSelection,
        loadFiles,
        uploadFiles,
        deleteSelectedFiles
    }
}
```

---

## Index.js Export Patterns

### Barrel Exports
```javascript
// components/index.js
export { default as Button } from './Button.vue'
export { default as Input } from './Input.vue'
export { default as Card } from './Card.vue'

// composables/index.js
export { useFileManagement } from './useFileManagement.js'
export { useProjectFolder } from './useProjectFolder.js'
export { useLayoutState } from './useLayoutState.js'

// utils/index.js
export * from './fileHelpers.js'
export * from './formatters.js'
export * from './validators.js'
```

### Grouped Exports
```javascript
// features/codemate/index.js
export * from './components'
export * from './composables'
export * from './stores'
export * from './utils'

// Or specific groupings
export const components = {
    FileExplorer: () => import('./components/FileExplorer.vue'),
    ProjectDropdown: () => import('./components/ProjectDropdown.vue')
}

export const services = {
    fileService: () => import('./services/fileService.js'),
    projectService: () => import('./services/projectService.js')
}
```

## Recommendations

1. **Choose consistency** - Pick one pattern and stick to it
2. **Use index.js files** - For cleaner imports and better organization
3. **Separate concerns** - Keep components, logic, and utilities separate
4. **Domain boundaries** - Clear separation between business domains
5. **Reusability** - Extract common patterns to shared locations
6. **Progressive complexity** - Organize from simple to complex
7. **TypeScript integration** - Add .ts files for type definitions and complex logic

Each structure has trade-offs - choose based on your team size, project complexity, and maintenance requirements.