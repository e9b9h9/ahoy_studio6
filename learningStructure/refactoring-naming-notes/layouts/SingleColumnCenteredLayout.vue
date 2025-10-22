<script setup>
const props = defineProps({
  maxWidth: {
    type: String,
    default: 'max-w-4xl'
  },
  containerPadding: {
    type: String,
    default: 'px-4 sm:px-6 lg:px-8'
  },
  contentPadding: {
    type: String,
    default: 'py-8'
  },
  showHeader: {
    type: Boolean,
    default: false
  },
  showFooter: {
    type: Boolean,
    default: false
  }
})
</script>

<template>
  <!--
    LAYOUT: Single column centered layout for focused content
    PURPOSE: Clean, centered content display with optional header and footer
    BEHAVIOR: Responsive centered content with consistent spacing
    
    WHEN TO USE:
    - Blog posts and articles
    - Documentation pages
    - About/contact pages
    - Landing pages
    - Single-focus forms
    - Error pages
    
    WHEN NOT TO USE:
    - Dashboard interfaces (use ThreeColumnFlexibleLayout)
    - Content editing (use TwoColumnToggleContainerLayout)
    - Complex applications (use studio templates)
    
    SLOTS:
    - header: Optional header content
    - main-content: Primary content area
    - footer: Optional footer content
    - sidebar: Optional floating sidebar for table of contents, etc.
    
    PROPS:
    - maxWidth: Maximum width constraint for content (default: 'max-w-4xl')
    - containerPadding: Horizontal padding classes (default: 'px-4 sm:px-6 lg:px-8')
    - contentPadding: Vertical padding classes (default: 'py-8')
    - showHeader: Whether to display header slot (default: false)
    - showFooter: Whether to display footer slot (default: false)
  -->
  <div class="min-h-screen bg-background">
    <!-- Optional Header -->
    <header v-if="showHeader || $slots.header" class="border-b border-border bg-background">
      <div :class="['mx-auto', maxWidth, containerPadding]">
        <slot name="header">
          <div class="py-4">
            <div class="text-lg text-muted-foreground">
              Header content
            </div>
          </div>
        </slot>
      </div>
    </header>
    
    <!-- Main Content Container -->
    <main class="flex-1">
      <div :class="['mx-auto', maxWidth, containerPadding, contentPadding]">
        <slot name="main-content">
          <div class="prose prose-lg mx-auto">
            <h1>Content Title</h1>
            <p class="text-muted-foreground">
              No main content provided. This layout centers content with a maximum width 
              and provides consistent spacing for readable content.
            </p>
          </div>
        </slot>
      </div>
    </main>
    
    <!-- Optional Floating Sidebar (for table of contents, etc.) -->
    <aside 
      v-if="$slots.sidebar" 
      class="fixed top-1/4 right-4 w-64 bg-background border border-border rounded-lg shadow-lg p-4 hidden xl:block"
    >
      <slot name="sidebar" />
    </aside>
    
    <!-- Optional Footer -->
    <footer v-if="showFooter || $slots.footer" class="border-t border-border bg-muted/5">
      <div :class="['mx-auto', maxWidth, containerPadding]">
        <slot name="footer">
          <div class="py-6 text-center text-sm text-muted-foreground">
            Footer content
          </div>
        </slot>
      </div>
    </footer>
  </div>
</template>

<style scoped>
/* Ensure proper typography for content */
.prose {
  color: var(--foreground);
}

.prose h1,
.prose h2,
.prose h3,
.prose h4,
.prose h5,
.prose h6 {
  color: var(--foreground);
}

/* Responsive sidebar behavior */
@media (max-width: 1279px) {
  aside {
    display: none !important;
  }
}

/* Smooth scrolling for anchor links */
html {
  scroll-behavior: smooth;
}

/* Focus styles for accessibility */
*:focus-visible {
  outline: 2px solid var(--ring);
  outline-offset: 2px;
}
</style>