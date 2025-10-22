<script setup>
import { ref, computed } from 'vue'
import TwoColumnToggleContainerLayout from '../../layouts/TwoColumnToggleContainerLayout.vue'

const props = defineProps({
  contentTitle: {
    type: String,
    default: ''
  },
  contentSubtitle: {
    type: String,
    default: ''
  },
  editingMode: {
    type: Boolean,
    default: false
  },
  showToolbar: {
    type: Boolean,
    default: true
  },
  showMetadata: {
    type: Boolean,
    default: true
  },
  lastSaved: {
    type: String,
    default: ''
  },
  wordCount: {
    type: Number,
    default: 0
  },
  autoSave: {
    type: Boolean,
    default: true
  }
})

const emit = defineEmits([
  'content-changed',
  'save-content',
  'toggle-editing',
  'publish-content',
  'preview-content'
])

// Template state
const isEditing = ref(props.editingMode)
const showPreview = ref(false)

// Computed properties
const saveStatusText = computed(() => {
  if (props.lastSaved) {
    return `Last saved: ${props.lastSaved}`
  }
  return props.autoSave ? 'Auto-save enabled' : 'Manual save required'
})

// Event handlers
const toggleEditing = () => {
  isEditing.value = !isEditing.value
  emit('toggle-editing', isEditing.value)
}

const handleSave = () => {
  emit('save-content')
}

const handlePublish = () => {
  emit('publish-content')
}

const togglePreview = () => {
  showPreview.value = !showPreview.value
  emit('preview-content', showPreview.value)
}

const handleContentChange = (content) => {
  emit('content-changed', content)
}
</script>

<template>
  <!--
    TEMPLATE: Editable content with sidebar navigation and tools
    PURPOSE: Content creation and editing with navigation, metadata, and tools
    INCLUDES: Sidebar navigation, content editor, toolbar, metadata display
    
    WHEN TO USE:
    - Blog post editing
    - Article writing
    - Documentation creation
    - Content management interfaces
    - Any text-based content editing
    
    WHEN NOT TO USE:
    - Code editing (use CodeEditorStudioTemplate)
    - Visual design (use DesignStudioTemplate)
    - Simple forms (use TwoColumnToggleContainerLayout directly)
    
    SLOTS:
    - navigation: Sidebar navigation content
    - toolbar: Custom toolbar buttons
    - content-editor: Main content editing area
    - metadata: Content metadata and settings
    - preview: Preview pane content
    - sidebar-footer: Footer content in sidebar
    
    PROPS:
    - contentTitle: Title of the content being edited
    - contentSubtitle: Subtitle or description
    - editingMode: Whether template starts in editing mode
    - showToolbar: Whether to show the editing toolbar
    - showMetadata: Whether to show metadata section
    - lastSaved: Last save timestamp text
    - wordCount: Current word count
    - autoSave: Whether auto-save is enabled
    
    EVENTS:
    - content-changed: When content is modified
    - save-content: When save action is triggered
    - toggle-editing: When editing mode is toggled
    - publish-content: When publish action is triggered
    - preview-content: When preview is toggled
  -->
  <TwoColumnToggleContainerLayout>
    <!-- Sidebar Navigation -->
    <template #sidebar-header>
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Content</h2>
        <div class="flex space-x-1">
          <button 
            @click="toggleEditing"
            :class="[
              'text-xs px-2 py-1 rounded transition-colors',
              isEditing 
                ? 'bg-blue-100 text-blue-700' 
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
          >
            {{ isEditing ? 'Editing' : 'View' }}
          </button>
        </div>
      </div>
    </template>
    
    <template #sidebar-content>
      <div class="space-y-6">
        <!-- Navigation -->
        <div>
          <h3 class="text-sm font-medium text-muted-foreground uppercase mb-3">Navigation</h3>
          <slot name="navigation">
            <div class="space-y-1">
              <div class="px-3 py-2 text-sm bg-blue-50 text-blue-700 rounded-md">
                📝 Current Document
              </div>
              <div class="px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-muted rounded-md cursor-pointer">
                📄 Recent Documents
              </div>
              <div class="px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-muted rounded-md cursor-pointer">
                📁 All Documents
              </div>
              <div class="px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-muted rounded-md cursor-pointer">
                🗂️ Categories
              </div>
            </div>
          </slot>
        </div>
        
        <!-- Metadata (when enabled) -->
        <div v-if="showMetadata">
          <h3 class="text-sm font-medium text-muted-foreground uppercase mb-3">Metadata</h3>
          <slot name="metadata">
            <div class="space-y-3 text-sm">
              <div class="p-3 bg-muted/30 rounded-lg">
                <div class="space-y-2">
                  <div class="flex justify-between">
                    <span class="text-muted-foreground">Status:</span>
                    <span class="font-medium">Draft</span>
                  </div>
                  <div v-if="wordCount > 0" class="flex justify-between">
                    <span class="text-muted-foreground">Words:</span>
                    <span class="font-medium">{{ wordCount.toLocaleString() }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-muted-foreground">Created:</span>
                    <span class="font-medium">Today</span>
                  </div>
                </div>
              </div>
              
              <div class="text-xs text-muted-foreground">
                {{ saveStatusText }}
              </div>
            </div>
          </slot>
        </div>
        
        <!-- Sidebar Footer -->
        <div class="mt-auto">
          <slot name="sidebar-footer">
            <div class="pt-4 border-t border-border">
              <div class="space-y-2">
                <button 
                  @click="handleSave"
                  class="w-full px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                >
                  Save Changes
                </button>
                <button 
                  @click="handlePublish"
                  class="w-full px-3 py-2 text-sm border border-border rounded-md hover:bg-muted transition-colors"
                >
                  Publish
                </button>
              </div>
            </div>
          </slot>
        </div>
      </div>
    </template>
    
    <!-- Main Content Area -->
    <template #main-content>
      <div class="h-full flex flex-col">
        <!-- Content Header -->
        <header class="border-b border-border p-6">
          <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
              <h1 class="text-2xl font-bold text-foreground mb-2">
                {{ contentTitle || 'Untitled Document' }}
              </h1>
              <p v-if="contentSubtitle" class="text-muted-foreground">
                {{ contentSubtitle }}
              </p>
            </div>
            
            <!-- Preview Toggle -->
            <button 
              v-if="isEditing"
              @click="togglePreview"
              :class="[
                'ml-4 px-3 py-2 text-sm rounded-md transition-colors',
                showPreview 
                  ? 'bg-green-100 text-green-700' 
                  : 'border border-border hover:bg-muted'
              ]"
            >
              {{ showPreview ? 'Hide Preview' : 'Preview' }}
            </button>
          </div>
        </header>
        
        <!-- Editing Toolbar (when in editing mode) -->
        <div v-if="isEditing && showToolbar" class="border-b border-border p-4 bg-muted/20">
          <slot name="toolbar">
            <div class="flex items-center space-x-2">
              <button class="p-2 hover:bg-muted rounded-md transition-colors" title="Bold">
                <strong>B</strong>
              </button>
              <button class="p-2 hover:bg-muted rounded-md transition-colors" title="Italic">
                <em>I</em>
              </button>
              <button class="p-2 hover:bg-muted rounded-md transition-colors" title="Underline">
                <u>U</u>
              </button>
              <div class="w-px h-6 bg-border mx-2"></div>
              <button class="p-2 hover:bg-muted rounded-md transition-colors" title="Heading">
                H1
              </button>
              <button class="p-2 hover:bg-muted rounded-md transition-colors" title="List">
                •
              </button>
              <button class="p-2 hover:bg-muted rounded-md transition-colors" title="Link">
                🔗
              </button>
              <button class="p-2 hover:bg-muted rounded-md transition-colors" title="Image">
                🖼️
              </button>
            </div>
          </slot>
        </div>
        
        <!-- Content Area -->
        <div class="flex-1 overflow-hidden">
          <div :class="['h-full', showPreview ? 'flex' : '']">
            <!-- Editor -->
            <div :class="['overflow-auto', showPreview ? 'w-1/2 border-r border-border' : 'w-full']">
              <slot name="content-editor" :is-editing="isEditing" :on-content-changed="handleContentChange">
                <div class="p-6">
                  <div v-if="isEditing" class="h-full">
                    <textarea 
                      class="w-full h-full resize-none border-none outline-none bg-transparent text-foreground placeholder-muted-foreground"
                      placeholder="Start writing your content..."
                      @input="handleContentChange($event.target.value)"
                    />
                  </div>
                  <div v-else class="prose max-w-none">
                    <h1>Content Preview</h1>
                    <p class="text-muted-foreground">
                      Your content would be displayed here in view mode. Switch to editing mode to make changes.
                    </p>
                  </div>
                </div>
              </slot>
            </div>
            
            <!-- Preview Pane -->
            <div v-if="showPreview" class="w-1/2 overflow-auto bg-muted/10">
              <slot name="preview">
                <div class="p-6">
                  <div class="prose max-w-none">
                    <h1>Live Preview</h1>
                    <p class="text-muted-foreground">
                      This is where the live preview of your content would appear as you type.
                    </p>
                  </div>
                </div>
              </slot>
            </div>
          </div>
        </div>
      </div>
    </template>
  </TwoColumnToggleContainerLayout>
</template>

<style scoped>
/* Ensure proper prose styling */
.prose {
  color: var(--foreground);
  max-width: none;
}

.prose h1,
.prose h2,
.prose h3,
.prose h4,
.prose h5,
.prose h6 {
  color: var(--foreground);
}

.prose p {
  margin-bottom: 1rem;
}

/* Custom textarea styling */
textarea {
  font-family: inherit;
  line-height: 1.6;
}

textarea:focus {
  outline: none;
}

/* Smooth transitions for mode switching */
.transition-all {
  transition: all 0.2s ease-in-out;
}
</style>