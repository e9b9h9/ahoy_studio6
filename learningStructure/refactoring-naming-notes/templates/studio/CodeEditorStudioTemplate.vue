<script setup>
import { ref, computed } from 'vue'
import ThreeColumnFlexibleLayout from '../../layouts/ThreeColumnFlexibleLayout.vue'

const props = defineProps({
  studioTitle: {
    type: String,
    default: 'Code Editor Studio'
  },
  initialLeftSidebarOpen: {
    type: Boolean,
    default: true
  },
  initialRightSidebarOpen: {
    type: Boolean,
    default: true
  },
  currentFilePath: {
    type: String,
    default: ''
  },
  fileStatus: {
    type: String,
    default: 'ready', // 'ready', 'loading', 'saving', 'error'
    validator: (value) => ['ready', 'loading', 'saving', 'error'].includes(value)
  },
  showTerminal: {
    type: Boolean,
    default: true
  },
  terminalHeight: {
    type: String,
    default: 'h-48'
  }
})

const emit = defineEmits([
  'file-selected',
  'file-saved',
  'file-run',
  'file-debug',
  'property-changed',
  'tool-selected',
  'refresh-files'
])

// Template state
const leftSidebarOpen = ref(props.initialLeftSidebarOpen)
const rightSidebarOpen = ref(props.initialRightSidebarOpen)
const terminalOpen = ref(props.showTerminal)

// File status indicator
const statusColor = computed(() => {
  switch (props.fileStatus) {
    case 'loading': return 'text-yellow-600'
    case 'saving': return 'text-blue-600'
    case 'error': return 'text-red-600'
    default: return 'text-green-600'
  }
})

const statusText = computed(() => {
  switch (props.fileStatus) {
    case 'loading': return 'Loading...'
    case 'saving': return 'Saving...'
    case 'error': return 'Error'
    default: return 'Ready'
  }
})

// Event handlers
const handleFileSelected = (file) => {
  emit('file-selected', file)
}

const handleSave = () => {
  emit('file-saved')
}

const handleRun = () => {
  emit('file-run')
}

const handleDebug = () => {
  emit('file-debug')
}

const handlePropertyChange = (property, value) => {
  emit('property-changed', property, value)
}

const handleToolSelected = (tool) => {
  emit('tool-selected', tool)
}

const handleRefreshFiles = () => {
  emit('refresh-files')
}

const toggleTerminal = () => {
  terminalOpen.value = !terminalOpen.value
}
</script>

<template>
  <!--
    TEMPLATE: Complete code editing studio environment
    PURPOSE: Provides full development workspace with file explorer, editor, terminal, and tools
    INCLUDES: File tree, code editor area, terminal, property inspector, tool palette
    
    WHEN TO USE:
    - Code editing and development work
    - Programming environments
    - Script editing interfaces
    - Any text-based development workflow
    
    WHEN NOT TO USE:
    - Simple text editing (use EditableContentTemplate)
    - Visual design work (use DesignStudioTemplate)
    - Content writing (use ContentWithSidebarTemplate)
    
    SLOTS:
    - file-explorer: File tree/browser component
    - editor-header: Additional header content above editor
    - code-editor: The main code editor component
    - editor-actions: Action buttons in the header
    - terminal: Terminal/console component
    - property-inspector: Properties panel for selected items
    - tool-palette: Available tools and actions
    - left-sidebar-header: Custom header for file explorer
    - right-sidebar-header: Custom header for inspector panel
    
    PROPS:
    - studioTitle: Title shown in header
    - initialLeftSidebarOpen: Initial state of file explorer
    - initialRightSidebarOpen: Initial state of inspector panel
    - currentFilePath: Path of currently open file
    - fileStatus: Status of current file (ready, loading, saving, error)
    - showTerminal: Whether to show terminal panel
    - terminalHeight: Height class for terminal panel
    
    EVENTS:
    - file-selected: When a file is selected in explorer
    - file-saved: When save action is triggered
    - file-run: When run action is triggered
    - file-debug: When debug action is triggered
    - property-changed: When a property is modified
    - tool-selected: When a tool is selected
    - refresh-files: When file tree refresh is requested
  -->
  <div class="h-full bg-background flex flex-col">
    <!-- Studio Header -->
    <header class="bg-background border-b border-border">
      <div class="flex items-center justify-between p-4">
        <div class="flex items-center space-x-4">
          <h1 class="text-lg font-semibold text-foreground">{{ studioTitle }}</h1>
          
          <!-- File Path and Status -->
          <div v-if="currentFilePath" class="flex items-center space-x-2 text-sm">
            <span class="text-muted-foreground">{{ currentFilePath }}</span>
            <span :class="['font-medium', statusColor]">{{ statusText }}</span>
          </div>
        </div>
        
        <!-- Header Actions -->
        <div class="flex items-center space-x-2">
          <slot name="editor-actions">
            <button 
              @click="handleSave"
              class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
            >
              Save
            </button>
            <button 
              @click="handleRun"
              class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700 transition-colors"
            >
              Run
            </button>
            <button 
              @click="handleDebug"
              class="px-3 py-1 text-sm border border-border rounded hover:bg-muted transition-colors"
            >
              Debug
            </button>
          </slot>
        </div>
      </div>
    </header>
    
    <!-- Main Studio Content -->
    <div class="flex-1 overflow-hidden">
      <ThreeColumnFlexibleLayout 
        :left-sidebar-open="leftSidebarOpen"
        :right-sidebar-open="rightSidebarOpen"
        @toggle-left-sidebar="leftSidebarOpen = !leftSidebarOpen"
        @toggle-right-sidebar="rightSidebarOpen = !rightSidebarOpen"
      >
        <!-- File Explorer Sidebar -->
        <template #left-sidebar-header>
          <slot name="left-sidebar-header">
            <div class="flex items-center justify-between">
              <h3 class="font-semibold">Project Files</h3>
              <button 
                @click="handleRefreshFiles"
                class="text-sm hover:text-blue-600 transition-colors p-1 rounded hover:bg-muted"
                title="Refresh file tree"
              >
                🔄
              </button>
            </div>
          </slot>
        </template>
        
        <template #left-sidebar>
          <slot name="file-explorer" :on-file-selected="handleFileSelected">
            <div class="p-4 text-sm text-muted-foreground">
              <div class="space-y-2">
                <div class="flex items-center space-x-2 cursor-pointer hover:text-foreground">
                  <span>📁</span>
                  <span>src/</span>
                </div>
                <div class="ml-4 flex items-center space-x-2 cursor-pointer hover:text-foreground">
                  <span>📄</span>
                  <span>App.vue</span>
                </div>
                <div class="ml-4 flex items-center space-x-2 cursor-pointer hover:text-foreground">
                  <span>📄</span>
                  <span>main.js</span>
                </div>
                <div class="flex items-center space-x-2 cursor-pointer hover:text-foreground">
                  <span>📁</span>
                  <span>components/</span>
                </div>
              </div>
            </div>
          </slot>
        </template>
        
        <!-- Main Editor Area -->
        <template #main-content>
          <div class="h-full flex flex-col">
            <!-- Editor Header -->
            <div v-if="$slots['editor-header']" class="border-b border-border p-4">
              <slot name="editor-header" />
            </div>
            
            <!-- Code Editor -->
            <div class="flex-1 overflow-hidden">
              <slot name="code-editor">
                <div class="h-full bg-gray-900 text-white p-4 font-mono text-sm">
                  <div class="text-gray-400">// Code editor placeholder</div>
                  <div class="text-blue-400 mt-2">&lt;template&gt;</div>
                  <div class="text-white ml-4">&lt;div class="hello-world"&gt;</div>
                  <div class="text-green-400 ml-8">Hello, World!</div>
                  <div class="text-white ml-4">&lt;/div&gt;</div>
                  <div class="text-blue-400">&lt;/template&gt;</div>
                  <div class="text-yellow-400 mt-4">&lt;script setup&gt;</div>
                  <div class="text-green-400 ml-4">// Your JavaScript code here</div>
                  <div class="text-yellow-400">&lt;/script&gt;</div>
                </div>
              </slot>
            </div>
            
            <!-- Terminal (when enabled) -->
            <div v-if="terminalOpen" :class="['border-t border-border bg-black text-green-400', terminalHeight]">
              <div class="flex items-center justify-between p-2 bg-gray-800 text-white">
                <span class="text-sm font-medium">Terminal</span>
                <button 
                  @click="toggleTerminal"
                  class="text-xs hover:text-red-400 transition-colors"
                >
                  ✕
                </button>
              </div>
              <div class="h-full overflow-auto">
                <slot name="terminal">
                  <div class="p-4 font-mono text-sm">
                    <div>$ npm run dev</div>
                    <div class="text-blue-400">ℹ Server running on http://localhost:3000</div>
                    <div class="text-green-400">✓ Ready in 1.2s</div>
                    <div class="mt-2">$</div>
                  </div>
                </slot>
              </div>
            </div>
            
            <!-- Terminal toggle button when closed -->
            <div v-else class="border-t border-border p-2 bg-muted/5">
              <button 
                @click="toggleTerminal"
                class="text-sm text-muted-foreground hover:text-foreground transition-colors"
              >
                ↑ Show Terminal
              </button>
            </div>
          </div>
        </template>
        
        <!-- Inspector/Tools Sidebar -->
        <template #right-sidebar-header>
          <slot name="right-sidebar-header">
            <h3 class="font-semibold">Inspector</h3>
          </slot>
        </template>
        
        <template #right-sidebar>
          <div class="space-y-6">
            <!-- Property Inspector -->
            <div>
              <h4 class="font-medium mb-3 text-sm text-muted-foreground uppercase">Properties</h4>
              <slot name="property-inspector" :on-property-changed="handlePropertyChange">
                <div class="space-y-2 text-sm">
                  <div class="border border-border rounded p-3 bg-background">
                    <div class="font-medium mb-2">Element Properties</div>
                    <div class="space-y-1 text-muted-foreground">
                      <div>Type: div</div>
                      <div>Class: hello-world</div>
                      <div>ID: —</div>
                    </div>
                  </div>
                </div>
              </slot>
            </div>
            
            <!-- Tool Palette -->
            <div>
              <h4 class="font-medium mb-3 text-sm text-muted-foreground uppercase">Tools</h4>
              <slot name="tool-palette" :on-tool-selected="handleToolSelected">
                <div class="grid grid-cols-2 gap-2">
                  <button class="p-3 border border-border rounded hover:bg-muted transition-colors text-center">
                    <div class="text-lg mb-1">👆</div>
                    <div class="text-xs">Select</div>
                  </button>
                  <button class="p-3 border border-border rounded hover:bg-muted transition-colors text-center">
                    <div class="text-lg mb-1">📝</div>
                    <div class="text-xs">Edit</div>
                  </button>
                  <button class="p-3 border border-border rounded hover:bg-muted transition-colors text-center">
                    <div class="text-lg mb-1">🔍</div>
                    <div class="text-xs">Search</div>
                  </button>
                  <button class="p-3 border border-border rounded hover:bg-muted transition-colors text-center">
                    <div class="text-lg mb-1">⚙️</div>
                    <div class="text-xs">Settings</div>
                  </button>
                </div>
              </slot>
            </div>
          </div>
        </template>
      </ThreeColumnFlexibleLayout>
    </div>
  </div>
</template>

<style scoped>
/* Ensure proper color inheritance for terminal */
.terminal {
  font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
}

/* Custom scrollbar for terminal */
.terminal::-webkit-scrollbar {
  width: 6px;
}

.terminal::-webkit-scrollbar-track {
  background: #1a1a1a;
}

.terminal::-webkit-scrollbar-thumb {
  background: #404040;
  border-radius: 3px;
}

.terminal::-webkit-scrollbar-thumb:hover {
  background: #505050;
}
</style>