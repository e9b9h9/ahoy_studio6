# Codemate Layout System Explanation

This document explains how the layout system works for the Codemate.vue page in your Laravel + Vue 3 + Inertia.js application.

## Layout Hierarchy Overview

The Codemate page uses a **nested layout system** with multiple layers of components working together:

```
Codemate.vue
├── AppLayout.vue (wrapper)
│   └── AppSidebarLayout.vue (actual layout implementation)
│       └── AppShell.vue (shell provider)
│           ├── AppSidebar.vue (navigation sidebar)
│           └── AppContent.vue (main content area)
│               └── AppSidebarHeader.vue (breadcrumbs header)
└── CodemateLayout.vue (custom layout for codemate features)
    ├── Header with toggle buttons
    ├── Left Sidebar (file tree)
    ├── Main Content Area
    └── Right Sidebar (processing tools)
```

## Detailed Component Analysis

### 1. AppLayout.vue (`/resources/js/layouts/AppLayout.vue`)

**Purpose**: This is a simple wrapper component that forwards props to the actual layout implementation.

```vue
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
```

**Key Features**:
- Acts as a proxy to `AppSidebarLayout.vue`
- Accepts breadcrumb data as props
- Uses TypeScript interfaces for type safety
- Simply passes content through via the default slot

### 2. AppSidebarLayout.vue (`/resources/js/layouts/app/AppSidebarLayout.vue`)

**Purpose**: The main application layout that provides the overall structure with navigation sidebar.

```vue
<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
    </AppShell>
</template>
```

**Key Features**:
- Uses `AppShell` as the container with sidebar variant
- Includes global navigation via `AppSidebar`
- Provides content area with `AppContent`
- Adds breadcrumb navigation via `AppSidebarHeader`
- Page content goes into the default slot

### 3. AppShell.vue (`/resources/js/components/AppShell.vue`)

**Purpose**: Provides the base shell structure and manages sidebar state.

```vue
<template>
    <SidebarProvider v-else :default-open="isOpen">
        <slot />
    </SidebarProvider>
</template>
```

**Key Features**:
- Uses Reka UI's `SidebarProvider` for sidebar state management
- Gets initial sidebar state from Inertia props (`sidebarOpen`)
- Supports both header and sidebar variants
- Provides context for all child sidebar components

### 4. AppSidebar.vue (`/resources/js/components/AppSidebar.vue`)

**Purpose**: The main navigation sidebar with app-wide navigation.

**Key Features**:
- Uses Reka UI sidebar components (`Sidebar`, `SidebarHeader`, etc.)
- Defines main navigation items (Dashboard, Podbud, Codemate, etc.)
- Includes app logo in header
- Footer with external links and user menu
- Collapsible to icon-only mode
- Navigation items use type-safe routes from Wayfinder

**Navigation Structure**:
```javascript
const mainNavItems = [
    { title: 'Dashboard', href: dashboard(), icon: LayoutGrid },
    { title: 'Podbud', href: podbudDashboard(), icon: Mic },
    { title: 'Codemate', href: codemate(), icon: Code },
    { title: 'Codemate Dashboard', href: codemateDashboard(), icon: BookOpen },
    { title: 'Codemate GitHub', href: codemateGithub(), icon: BookOpen }
];
```

### 5. CodemateLayout.vue (`/resources/js/pages/codemate/CodemateLayout.vue`)

**Purpose**: A specialized layout specifically for Codemate functionality with its own sidebar system.

**Layout Structure**:
```vue
<template>
    <div class="flex h-full flex-col">
        <!-- Header with toggle buttons and tools -->
        <header class="flex h-14 items-center gap-4 border-b">
            <Button @click="toggleLeftSidebar">Toggle Left</Button>
            <slot name="header-content"></slot>
            <Button @click="toggleRightSidebar">Toggle Right</Button>
        </header>

        <!-- Three-column layout -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Left Sidebar (1/4 width) -->
            <aside v-if="leftSidebarOpen" class="w-1/4">
                <slot name="left-sidebar"></slot>
            </aside>

            <!-- Main Content (flexible) -->
            <main class="flex-1">
                <slot name="main-content"></slot>
            </main>

            <!-- Right Sidebar (1/4 width) -->
            <aside v-if="rightSidebarOpen" class="w-1/4">
                <slot name="right-sidebar-top"></slot>
            </aside>
        </div>
    </div>
</template>
```

**Key Features**:
- **Independent sidebar system**: Separate from the main app sidebar
- **Three-column layout**: Left sidebar (25%), Main content (50%), Right sidebar (25%)
- **Toggleable sidebars**: Both left and right sidebars can be hidden
- **Named slots**: Different areas for different types of content
- **Reactive state**: Uses Vue 3's `ref()` for sidebar toggle state
- **Tailwind CSS**: Uses utility classes for layout and styling

## How Codemate.vue Uses These Layouts

In `Codemate.vue`, the layouts are used in this nested structure:

```vue
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <CodemateLayout>
            <!-- Header tools -->
            <template #header-content>
                <ProjectFolderDropdown />
                <RefreshFiles />
                <MountFiles />
                <ShowAllFolderFiles />
            </template>
            
            <!-- Left sidebar: File navigation -->
            <template #left-sidebar>
                <WatchedFolderTree />
                <MountedFileTree />
            </template>
            
            <!-- Right sidebar: Processing tools -->
            <template #right-sidebar-top>
                <ProcessMountedFile />
            </template>
        </CodemateLayout>
    </AppLayout>
</template>
```

## Slot System Explanation

### AppLayout Slots
- **Default slot**: Contains the entire page content (CodemateLayout in this case)

### CodemateLayout Slots
- **`header-content`**: Action buttons and dropdowns in the header
- **`left-sidebar`**: File tree components for navigation
- **`main-content`**: Primary workspace (currently empty, falls back to default text)
- **`right-sidebar-top`**: File processing and analysis tools

## State Management

### Global Sidebar (AppSidebar)
- State managed by Reka UI's `SidebarProvider`
- Initial state comes from Laravel backend via Inertia props
- Persists across page navigation

### Codemate Sidebars (CodemateLayout)
- Local state using Vue 3's `ref()`
- Independent toggle buttons for each sidebar
- State resets when navigating away from the page
- Default to open (`leftSidebarOpen.value = true`)

## Styling and Responsiveness

### Layout Structure
- Uses Flexbox for responsive layouts
- Fixed header height (`h-14` = 56px)
- Flexible content area that fills remaining height
- Sidebar widths are percentage-based (`w-1/4` = 25%)

### Tailwind Classes Used
- `flex`, `flex-col`, `flex-1`: Flexbox layout
- `h-full`, `h-14`: Height utilities
- `w-1/4`: Width utilities (25%)
- `border-r`, `border-l`, `border-b`: Borders
- `bg-muted/10`: Background with opacity
- `p-4`, `p-6`: Padding
- `transition-all duration-300`: Smooth sidebar animations

## Benefits of This Architecture

1. **Separation of Concerns**: Global navigation vs. feature-specific layout
2. **Reusability**: AppLayout can be used by other pages
3. **Flexibility**: CodemateLayout provides specialized UI for complex features
4. **Maintainability**: Each layout component has a single responsibility
5. **Type Safety**: TypeScript interfaces ensure prop consistency
6. **User Experience**: Independent sidebar controls for better workspace management

## Integration with Other Systems

### Inertia.js Integration
- Breadcrumbs passed as props from Laravel controllers
- Sidebar state synchronized with backend
- Type-safe navigation using Wayfinder routes

### Store Integration
- Codemate store initialization in `onMounted()`
- Props passed to store for initial state
- Store manages file tree data and processing state

### Component Communication
- Parent-child communication via props and slots
- Event handling for sidebar toggles
- Store-based state for complex data management

This nested layout system provides a powerful and flexible foundation for building complex file management interfaces while maintaining clean separation between global navigation and feature-specific UI elements.