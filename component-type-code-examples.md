# Component Type Code Examples

This document provides code snippets and examples from the features folder, organized by component type and complexity level. Each section shows actual code from the project to illustrate different architectural patterns.

---

## 1. Basic Building Blocks
*Simple, reusable UI elements*

### Card UI Components
**File Path:** `features/testing/card/CardHeader.vue` (File does not exist - referenced in index.ts)
**File Path:** `features/testing/card/index.ts`

```typescript
export { default as Card } from './Card.vue'
export { default as CardAction } from './CardAction.vue'
export { default as CardContent } from './CardContent.vue'
export { default as CardDescription } from './CardDescription.vue'
export { default as CardFooter } from './CardFooter.vue'
export { default as CardHeader } from './CardHeader.vue'
export { default as CardTitle } from './CardTitle.vue'
```
*Note: These components exist in exports but actual .vue files are missing*

---

## 2. Simple Content Components
*Basic content with minimal logic*

### Static Content Example
**File Path:** `features/testing/HeaderTools.vue` (entire file)

```vue
<script setup>
// Simple header tools with basic HTML elements
</script>

<template>
    <h4 class="text-sm font-medium">Testing Tools</h4>
    <span class="text-xs text-muted-foreground">Active</span>
</template>
```

### Configurable Content Example
**File Path:** `features/testing/sample/SampleRecentFiles.vue` (entire file)

```vue
<script setup>
const props = defineProps({
    files: {
        type: Array,
        default: () => [
            'sample-1.vue',
            'sample-2.vue', 
            'sample-3.vue'
        ]
    },
    title: {
        type: String,
        default: 'Recent'
    }
});
</script>

<template>
    <div class="space-y-2">
        <h4 class="text-xs font-semibold text-muted-foreground uppercase">{{ title }}</h4>
        <div class="space-y-1">
            <p 
                v-for="file in files" 
                :key="file"
                class="text-xs text-muted-foreground"
            >
                📄 {{ file }}
            </p>
        </div>
    </div>
</template>
```

---

## 3. Core Business Components
*Components with business logic and state management*

### Pinia Store Integration Example
**File Path:** `features/codemate/ProjectFolderDropdown.vue` (script section)

```vue
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
```

### API Integration with Business Logic
**File Path:** `features/codemate/MountFiles.vue` (script section)

```vue
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
```

---

## 4. Configuration Components
*Settings and form management*

### Simple Form Component
**File Path:** `features/codemate-settings/AddProjectFolder.vue` (entire file)

```vue
<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    path: ''
});

const handleSubmit = () => {
    if (form.path.trim()) {
        form.post('/codemate/folder-files', {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
            }
        });
    }
};
</script>

<template>
<input 
      v-model="form.path"
      @keyup.enter="handleSubmit"
      type="text" 
      placeholder="Enter folder name..."
      class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
      :disabled="form.processing"
    />
</template>
```

---

## 5. Data Display Components
*Components that render complex data structures*

### Tree Component with Props
**File Path:** `features/codemate/FolderFileTree.vue` (props definition)

```vue
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
</script>
```

---

## 6. Layout Components
*Structural components with slot-based composition*

### Pure Layout Component
**File Path:** `features/testing/layouts/ThreeColumnLayout.vue` (layout structure)

```vue
<script setup>
const props = defineProps({
    leftSidebarOpen: {
        type: Boolean,
        default: true
    },
    rightSidebarOpen: {
        type: Boolean,
        default: true
    }
});
</script>

<template>
    <!-- Three-column layout -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Left Sidebar -->
        <aside 
            v-if="leftSidebarOpen"
            class="w-1/4 border-r border-border bg-muted/10 p-4 transition-all duration-300"
        >
            <slot name="left-sidebar"></slot>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <slot name="main-content"></slot>
        </main>

        <!-- Right Sidebar -->
        <aside 
            v-if="rightSidebarOpen"
            class="w-1/4 border-l border-border bg-muted/10 p-4 transition-all duration-300"
        >
            <slot name="right-sidebar"></slot>
        </aside>
    </div>
</template>
```

---

## 7. Composite/Organism Components
*Complex assemblies that combine multiple components*

### Layout Composite with State Management
**File Path:** `features/testing/layouts/SidebarWithTestingLayout.vue` (component composition)

```vue
<script setup>
import StudioBreadcrumbs from '@/features/testing/StudioBreadcrumbs.vue';
import TestingWorkspace from '@/features/testing/TestingWorkspace.vue';
import { ref } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: 'Testing Page'
    }
});

const mainSidebarOpen = ref(true);

const toggleMainSidebar = () => {
    mainSidebarOpen.value = !mainSidebarOpen.value;
};
</script>

<template>
    <div class="min-h-screen bg-background flex">
        <!-- Main Left Sidebar -->
        <aside 
            v-if="mainSidebarOpen"
            class="w-64 border-r border-border bg-muted/5 p-4 transition-all duration-300"
        >
            <slot name="main-sidebar">
                <!-- Default content when no slot provided -->
            </slot>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 min-w-0 flex flex-col">
            <StudioBreadcrumbs :title="props.title" />
            
            <div class="flex-1">
                <TestingWorkspace :title="props.title">
                    <template #header-content>
                        <slot name="header-content"></slot>
                    </template>
                    <!-- Additional slot passthroughs -->
                </TestingWorkspace>
            </div>
        </div>
    </div>
</template>
```

---

## 8. Utilities/Wrappers
*Helper components with specific functionality*

### Breadcrumb Utility
**File Path:** `features/testing/StudioBreadcrumbs.vue` (utility component)

```vue
<script setup>
const props = defineProps({
    title: {
        type: String,
        default: 'Page'
    }
});
</script>

<template>
    <div class="px-6 py-3 border-b border-border bg-muted/5">
        <nav class="flex items-center space-x-2 text-sm text-muted-foreground">
            <span>🏠 Home</span>
            <span>/</span>
            <span class="text-foreground font-medium">{{ title }}</span>
        </nav>
    </div>
</template>
```

---

## Component Complexity Patterns

### Simple → Complex Progression

1. **Static HTML** (`HeaderTools.vue`)
   - No props, no logic, pure presentation

2. **Configurable Content** (`SampleRecentFiles.vue`)
   - Props for customization, basic rendering logic

3. **Business Logic** (`ProjectFolderDropdown.vue`)
   - Store integration, event handling, business rules

4. **Data Management** (`FolderFileTree.vue`)
   - API calls, complex props, data transformation

5. **Layout Orchestration** (`ThreeColumnLayout.vue`)
   - Slot management, responsive behavior, pure structure

6. **Complex Assembly** (`SidebarWithTestingLayout.vue`)
   - Multiple component composition, state management, slot passthrough

---

## Architecture Insights

### Reusability Patterns
- **High Reusability**: Layout components with slots
- **Medium Reusability**: Configurable content components with props
- **Low Reusability**: Business logic components tied to specific stores

### Complexity Indicators
- **Simple**: No imports, minimal props, static content
- **Medium**: Store integration, API calls, event handling
- **Complex**: Multiple component composition, state management, slot orchestration

### Naming Conventions
- **Layout Components**: End with "Layout" 
- **Content Components**: Descriptive names without special suffixes
- **Sample Components**: Prefix with "Sample"
- **Settings Components**: Often include "Add", "Show", or action verbs