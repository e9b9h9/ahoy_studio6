<script setup>
import { ref } from 'vue'

const props = defineProps({
  initialSidebarOpen: {
    type: Boolean,
    default: true
  },
  sidebarWidth: {
    type: String,
    default: 'w-64'
  }
})

const sidebarOpen = ref(props.initialSidebarOpen)

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value
}

// Expose toggle function for parent components
defineExpose({
  toggleSidebar,
  sidebarOpen: readonly(sidebarOpen)
})
</script>

<template>
  <!--
    LAYOUT: Two-column layout with sidebar toggle functionality
    PURPOSE: Main content area with collapsible sidebar
    BEHAVIOR: Sidebar can be toggled open/closed with smooth transitions
    
    WHEN TO USE:
    - Content editing pages
    - File browsing interfaces
    - Settings pages with navigation
    - Documentation with sidebar navigation
    
    WHEN NOT TO USE:
    - Dashboards (use ThreeColumnFlexibleLayout)
    - Simple content display (use SingleColumnCenteredLayout)
    - Studio environments (use studio templates)
    
    SLOTS:
    - sidebar-header: Content for sidebar header area
    - sidebar-content: Main sidebar content
    - main-content: Primary content area
    
    PROPS:
    - initialSidebarOpen: Whether sidebar starts open (default: true)
    - sidebarWidth: Tailwind width class for sidebar (default: 'w-64')
  -->
  <div class="min-h-screen bg-background flex">
    <!-- Toggleable Sidebar -->
    <aside 
      v-if="sidebarOpen"
      :class="[sidebarWidth, 'border-r border-border bg-muted/5 transition-all duration-300 ease-in-out']"
    >
      <div class="h-full flex flex-col">
        <!-- Sidebar Header -->
        <div class="p-4 border-b border-border">
          <div class="flex items-center justify-between">
            <slot name="sidebar-header">
              <h2 class="text-lg font-semibold text-foreground">Navigation</h2>
            </slot>
            <button 
              @click="toggleSidebar"
              class="text-sm text-muted-foreground hover:text-foreground transition-colors p-1 rounded hover:bg-muted"
              aria-label="Close sidebar"
            >
              ←
            </button>
          </div>
        </div>
        
        <!-- Sidebar Content -->
        <div class="flex-1 p-4 overflow-y-auto">
          <slot name="sidebar-content">
            <div class="text-sm text-muted-foreground">
              No sidebar content provided
            </div>
          </slot>
        </div>
      </div>
    </aside>
    
    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0">
      <!-- Header with toggle when sidebar is closed -->
      <header 
        v-if="!sidebarOpen" 
        class="p-4 border-b border-border bg-background"
      >
        <button 
          @click="toggleSidebar"
          class="inline-flex items-center space-x-2 text-sm text-muted-foreground hover:text-foreground transition-colors px-3 py-2 rounded-md hover:bg-muted"
          aria-label="Open sidebar"
        >
          <span>→</span>
          <span>Show Sidebar</span>
        </button>
      </header>
      
      <!-- Main Content -->
      <div class="flex-1 overflow-auto">
        <slot name="main-content">
          <div class="p-6">
            <div class="text-lg text-muted-foreground">
              No main content provided
            </div>
          </div>
        </slot>
      </div>
    </main>
  </div>
</template>

<style scoped>
/* Additional styling for smooth transitions */
.transition-all {
  transition-property: all;
}

/* Ensure content doesn't overflow when sidebar toggles */
main {
  min-width: 0; /* Allows flex child to shrink below content size */
}
</style>