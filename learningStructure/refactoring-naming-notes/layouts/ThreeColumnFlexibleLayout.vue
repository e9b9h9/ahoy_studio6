<script setup>
const props = defineProps({
  leftSidebarOpen: {
    type: Boolean,
    default: true
  },
  rightSidebarOpen: {
    type: Boolean,
    default: true
  },
  leftSidebarWidth: {
    type: String,
    default: 'w-1/4'
  },
  rightSidebarWidth: {
    type: String,
    default: 'w-1/4'
  },
  minMainWidth: {
    type: String,
    default: 'min-w-0'
  }
})

const emit = defineEmits(['toggle-left-sidebar', 'toggle-right-sidebar'])

const toggleLeftSidebar = () => {
  emit('toggle-left-sidebar')
}

const toggleRightSidebar = () => {
  emit('toggle-right-sidebar')
}
</script>

<template>
  <!--
    LAYOUT: Three-column layout with flexible sidebar controls
    PURPOSE: Left sidebar + main content + right sidebar with independent toggle controls
    BEHAVIOR: Each sidebar can be toggled independently with responsive behavior
    
    WHEN TO USE:
    - Studio environments (code editors, design tools)
    - Dashboard pages with multiple panels
    - Content editing with tools and properties
    - Admin interfaces with navigation and details
    
    WHEN NOT TO USE:
    - Simple content display (use TwoColumnToggleContainerLayout)
    - Mobile-first designs (too many columns)
    - Single-focus tasks (use SingleColumnCenteredLayout)
    
    SLOTS:
    - left-sidebar: Content for left sidebar
    - main-content: Primary content area
    - right-sidebar: Content for right sidebar
    - main-header: Optional header above main content
    
    PROPS:
    - leftSidebarOpen: Controls left sidebar visibility
    - rightSidebarOpen: Controls right sidebar visibility  
    - leftSidebarWidth: Tailwind width class for left sidebar
    - rightSidebarWidth: Tailwind width class for right sidebar
    - minMainWidth: Minimum width class for main content
    
    EVENTS:
    - toggle-left-sidebar: Emitted when left sidebar should toggle
    - toggle-right-sidebar: Emitted when right sidebar should toggle
  -->
  <div class="flex flex-1 overflow-hidden h-full">
    <!-- Left Sidebar -->
    <aside 
      v-if="leftSidebarOpen"
      :class="[
        leftSidebarWidth, 
        'border-r border-border bg-muted/5 transition-all duration-300 ease-in-out flex-shrink-0'
      ]"
    >
      <div class="h-full flex flex-col">
        <!-- Left Sidebar Header -->
        <div class="p-4 border-b border-border">
          <div class="flex items-center justify-between">
            <slot name="left-sidebar-header">
              <h3 class="font-semibold text-foreground">Left Panel</h3>
            </slot>
            <button 
              @click="toggleLeftSidebar"
              class="text-sm text-muted-foreground hover:text-foreground transition-colors p-1 rounded hover:bg-muted"
              aria-label="Toggle left sidebar"
            >
              ←
            </button>
          </div>
        </div>
        
        <!-- Left Sidebar Content -->
        <div class="flex-1 overflow-y-auto">
          <slot name="left-sidebar">
            <div class="p-4 text-sm text-muted-foreground">
              No left sidebar content provided
            </div>
          </slot>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main :class="['flex-1 flex flex-col', minMainWidth]">
      <!-- Optional Main Header -->
      <header v-if="$slots['main-header']" class="border-b border-border bg-background">
        <slot name="main-header" />
      </header>
      
      <!-- Toggle buttons when sidebars are closed -->
      <div 
        v-if="!leftSidebarOpen || !rightSidebarOpen" 
        class="p-2 border-b border-border bg-muted/5 flex justify-between"
      >
        <button 
          v-if="!leftSidebarOpen"
          @click="toggleLeftSidebar"
          class="text-xs text-muted-foreground hover:text-foreground transition-colors px-2 py-1 rounded hover:bg-muted"
        >
          → Show Left Panel
        </button>
        <div v-else></div>
        
        <button 
          v-if="!rightSidebarOpen"
          @click="toggleRightSidebar"
          class="text-xs text-muted-foreground hover:text-foreground transition-colors px-2 py-1 rounded hover:bg-muted"
        >
          Show Right Panel ←
        </button>
      </div>
      
      <!-- Main Content -->
      <div class="flex-1 overflow-auto">
        <slot name="main-content">
          <div class="p-6">
            <div class="text-lg text-muted-foreground text-center">
              No main content provided
            </div>
          </div>
        </slot>
      </div>
    </main>

    <!-- Right Sidebar -->
    <aside 
      v-if="rightSidebarOpen"
      :class="[
        rightSidebarWidth,
        'border-l border-border bg-muted/5 transition-all duration-300 ease-in-out flex-shrink-0'
      ]"
    >
      <div class="h-full flex flex-col">
        <!-- Right Sidebar Header -->
        <div class="p-4 border-b border-border">
          <div class="flex items-center justify-between">
            <slot name="right-sidebar-header">
              <h3 class="font-semibold text-foreground">Right Panel</h3>
            </slot>
            <button 
              @click="toggleRightSidebar"
              class="text-sm text-muted-foreground hover:text-foreground transition-colors p-1 rounded hover:bg-muted"
              aria-label="Toggle right sidebar"
            >
              →
            </button>
          </div>
        </div>
        
        <!-- Right Sidebar Content -->
        <div class="flex-1 overflow-y-auto">
          <slot name="right-sidebar">
            <div class="p-4 text-sm text-muted-foreground">
              No right sidebar content provided
            </div>
          </slot>
        </div>
      </div>
    </aside>
  </div>
</template>

<style scoped>
/* Ensure smooth transitions and proper flex behavior */
.transition-all {
  transition-property: all;
}

/* Prevent content overflow issues */
main {
  min-width: 0;
}

aside {
  min-width: 200px; /* Minimum width to prevent sidebar from becoming too narrow */
}

/* Responsive behavior - hide sidebars on very small screens */
@media (max-width: 768px) {
  aside {
    position: absolute;
    top: 0;
    bottom: 0;
    z-index: 50;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  }
  
  aside:first-child {
    left: 0;
  }
  
  aside:last-child {
    right: 0;
  }
}
</style>