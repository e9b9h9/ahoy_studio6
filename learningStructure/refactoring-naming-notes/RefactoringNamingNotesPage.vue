<script setup>
import { ref } from 'vue'

const activeExample = ref('overview')
const showLayoutExample = ref(false)
const showTemplateExample = ref(false)
const showComponentExample = ref(false)

const examples = [
  { id: 'overview', label: 'Overview', icon: '📋' },
  { id: 'layouts', label: 'Layouts', icon: '🏗️' },
  { id: 'templates', label: 'Templates', icon: '📄' },
  { id: 'components', label: 'Components', icon: '🧩' },
  { id: 'usage', label: 'Usage Examples', icon: '💡' }
]

const handleExampleSelect = (example) => {
  activeExample.value = example.id
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Refactoring & Naming Notes</h1>
            <p class="text-sm text-gray-600">Component organization and naming convention examples</p>
          </div>
          <div class="flex space-x-2">
            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">Learning Structure</span>
            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">Best Practices</span>
          </div>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex gap-8">
        <!-- Navigation Sidebar -->
        <aside class="w-64 flex-shrink-0">
          <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold mb-4">Examples</h3>
            <nav class="space-y-2">
              <div 
                v-for="example in examples" 
                :key="example.id"
                @click="handleExampleSelect(example)"
                class="flex items-center space-x-3 px-3 py-2 rounded-md cursor-pointer transition-colors"
                :class="activeExample === example.id 
                  ? 'bg-blue-50 text-blue-700 border border-blue-200' 
                  : 'text-gray-700 hover:bg-gray-50'"
              >
                <span>{{ example.icon }}</span>
                <span class="font-medium">{{ example.label }}</span>
              </div>
            </nav>
          </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
          <div class="bg-white rounded-lg shadow-sm border">
            
            <!-- Overview Section -->
            <div v-if="activeExample === 'overview'" class="p-8">
              <h2 class="text-3xl font-bold mb-6">Component Organization Overview</h2>
              
              <div class="prose max-w-none">
                <p class="text-lg text-gray-600 mb-6">
                  This page demonstrates our revised component organization structure with 
                  descriptive, action-based naming conventions.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                  <div class="border rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-3 text-green-700">✅ Good Naming</h3>
                    <ul class="space-y-2 text-sm">
                      <li><code class="bg-gray-100 px-2 py-1 rounded">TwoColumnToggleContainerLayout.vue</code></li>
                      <li><code class="bg-gray-100 px-2 py-1 rounded">StaticVerticalNavigationMenu.vue</code></li>
                      <li><code class="bg-gray-100 px-2 py-1 rounded">RecentlyAccessedFilesList.vue</code></li>
                      <li><code class="bg-gray-100 px-2 py-1 rounded">CodeEditorStudioTemplate.vue</code></li>
                    </ul>
                  </div>
                  
                  <div class="border rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-3 text-red-700">❌ Avoid These</h3>
                    <ul class="space-y-2 text-sm">
                      <li><code class="bg-gray-100 px-2 py-1 rounded">AppLayout.vue</code> <span class="text-gray-500">(too generic)</span></li>
                      <li><code class="bg-gray-100 px-2 py-1 rounded">NavigationMenu.vue</code> <span class="text-gray-500">(not descriptive)</span></li>
                      <li><code class="bg-gray-100 px-2 py-1 rounded">RecentFiles.vue</code> <span class="text-gray-500">(forgettable)</span></li>
                      <li><code class="bg-gray-100 px-2 py-1 rounded">SampleComponent.vue</code> <span class="text-gray-500">(sample prefix)</span></li>
                    </ul>
                  </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                  <h3 class="text-lg font-semibold text-blue-800 mb-3">Folder Structure</h3>
                  <pre class="text-sm text-blue-800 overflow-x-auto"><code>refactoring-naming-notes/
├── layouts/              # Pure structural layouts
├── templates/            # Reusable templates with defaults
├── components/           # Reusable UI components
├── usage-examples/       # Live documentation
└── RefactoringNamingNotesPage.vue</code></pre>
                </div>
              </div>
            </div>

            <!-- Layouts Section -->
            <div v-if="activeExample === 'layouts'" class="p-8">
              <h2 class="text-3xl font-bold mb-6">Layout Examples</h2>
              
              <div class="space-y-8">
                <div class="border rounded-lg p-6">
                  <h3 class="text-xl font-semibold mb-4">TwoColumnToggleContainerLayout</h3>
                  <p class="text-gray-600 mb-4">
                    A two-column layout with a toggleable sidebar. Perfect for content browsing with optional navigation.
                  </p>
                  
                  <div class="bg-gray-100 rounded-lg p-4 mb-4">
                    <div class="flex border rounded bg-white" style="height: 200px;">
                      <div class="w-1/4 bg-blue-50 border-r p-4 flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                          <span class="font-medium">Sidebar</span>
                          <button class="text-xs bg-gray-200 px-2 py-1 rounded">←</button>
                        </div>
                        <div class="space-y-2">
                          <div class="h-2 bg-gray-300 rounded"></div>
                          <div class="h-2 bg-gray-300 rounded w-3/4"></div>
                          <div class="h-2 bg-gray-300 rounded w-1/2"></div>
                        </div>
                      </div>
                      <div class="flex-1 p-4">
                        <div class="h-4 bg-gray-300 rounded mb-4"></div>
                        <div class="space-y-2">
                          <div class="h-2 bg-gray-200 rounded"></div>
                          <div class="h-2 bg-gray-200 rounded"></div>
                          <div class="h-2 bg-gray-200 rounded w-2/3"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <button 
                    @click="showLayoutExample = !showLayoutExample"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                  >
                    {{ showLayoutExample ? 'Hide' : 'Show' }} Code Example
                  </button>
                  
                  <div v-if="showLayoutExample" class="mt-4 bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto">
                    <pre><code>&lt;!-- layouts/TwoColumnToggleContainerLayout.vue --&gt;
&lt;template&gt;
  &lt;div class="min-h-screen bg-background flex"&gt;
    &lt;!-- Toggleable Sidebar --&gt;
    &lt;aside v-if="sidebarOpen" class="w-64 border-r"&gt;
      &lt;slot name="sidebar-content" /&gt;
    &lt;/aside&gt;
    
    &lt;!-- Main Content --&gt;
    &lt;main class="flex-1"&gt;
      &lt;slot name="main-content" /&gt;
    &lt;/main&gt;
  &lt;/div&gt;
&lt;/template&gt;</code></pre>
                  </div>
                </div>

                <div class="border rounded-lg p-6">
                  <h3 class="text-xl font-semibold mb-4">ThreeColumnFlexibleLayout</h3>
                  <p class="text-gray-600 mb-4">
                    A three-column layout with flexible sidebar widths. Ideal for studio environments with multiple panels.
                  </p>
                  
                  <div class="bg-gray-100 rounded-lg p-4">
                    <div class="flex border rounded bg-white" style="height: 200px;">
                      <div class="w-1/4 bg-green-50 border-r p-2">
                        <div class="text-xs font-medium mb-2">Left Panel</div>
                        <div class="space-y-1">
                          <div class="h-1 bg-gray-300 rounded"></div>
                          <div class="h-1 bg-gray-300 rounded"></div>
                        </div>
                      </div>
                      <div class="flex-1 p-2">
                        <div class="text-xs font-medium mb-2">Main Content</div>
                        <div class="h-3 bg-gray-300 rounded mb-2"></div>
                        <div class="space-y-1">
                          <div class="h-1 bg-gray-200 rounded"></div>
                          <div class="h-1 bg-gray-200 rounded"></div>
                        </div>
                      </div>
                      <div class="w-1/4 bg-purple-50 border-l p-2">
                        <div class="text-xs font-medium mb-2">Right Panel</div>
                        <div class="space-y-1">
                          <div class="h-1 bg-gray-300 rounded"></div>
                          <div class="h-1 bg-gray-300 rounded w-2/3"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Templates Section -->
            <div v-if="activeExample === 'templates'" class="p-8">
              <h2 class="text-3xl font-bold mb-6">Template Examples</h2>
              
              <div class="space-y-8">
                <div class="border rounded-lg p-6">
                  <h3 class="text-xl font-semibold mb-4">CodeEditorStudioTemplate</h3>
                  <p class="text-gray-600 mb-4">
                    Complete development environment with file explorer, code editor, terminal, and inspector panels.
                  </p>
                  
                  <div class="bg-gray-100 rounded-lg p-4 mb-4">
                    <div class="bg-white rounded border" style="height: 300px;">
                      <!-- Studio Header -->
                      <div class="border-b p-3 bg-gray-50">
                        <div class="flex items-center justify-between">
                          <span class="text-sm font-medium">CodeEditor Studio</span>
                          <div class="flex space-x-2">
                            <button class="text-xs bg-blue-500 text-white px-2 py-1 rounded">Run</button>
                            <button class="text-xs bg-gray-300 px-2 py-1 rounded">Debug</button>
                          </div>
                        </div>
                      </div>
                      
                      <div class="flex" style="height: calc(100% - 49px);">
                        <!-- File Explorer -->
                        <div class="w-1/4 border-r p-3">
                          <div class="text-xs font-medium mb-2">Project Files</div>
                          <div class="space-y-1 text-xs">
                            <div>📁 src/</div>
                            <div class="ml-3">📄 App.vue</div>
                            <div class="ml-3">📄 main.js</div>
                            <div>📁 components/</div>
                          </div>
                        </div>
                        
                        <!-- Main Editor -->
                        <div class="flex-1 flex flex-col">
                          <div class="flex-1 bg-gray-900 p-3">
                            <div class="text-green-400 text-xs font-mono">
                              <div>&lt;template&gt;</div>
                              <div>&nbsp;&nbsp;&lt;div&gt;Hello World&lt;/div&gt;</div>
                              <div>&lt;/template&gt;</div>
                            </div>
                          </div>
                          <!-- Terminal -->
                          <div class="h-16 bg-black p-2 border-t">
                            <div class="text-green-400 text-xs font-mono">$ npm run dev</div>
                          </div>
                        </div>
                        
                        <!-- Inspector -->
                        <div class="w-1/4 border-l p-3">
                          <div class="text-xs font-medium mb-2">Inspector</div>
                          <div class="space-y-2 text-xs">
                            <div class="bg-gray-100 p-2 rounded">Properties</div>
                            <div class="bg-gray-100 p-2 rounded">Tools</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <button 
                    @click="showTemplateExample = !showTemplateExample"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                  >
                    {{ showTemplateExample ? 'Hide' : 'Show' }} Usage Example
                  </button>
                  
                  <div v-if="showTemplateExample" class="mt-4 bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto">
                    <pre><code>&lt;!-- How to use CodeEditorStudioTemplate --&gt;
&lt;CodeEditorStudioTemplate&gt;
  &lt;template #editor-actions&gt;
    &lt;button @click="runCode"&gt;Run Code&lt;/button&gt;
    &lt;button @click="debugCode"&gt;Debug&lt;/button&gt;
  &lt;/template&gt;
  
  &lt;template #code-editor&gt;
    &lt;MonacoEditor v-model="code" /&gt;
  &lt;/template&gt;
  
  &lt;template #file-explorer&gt;
    &lt;ProjectFileExplorer :files="projectFiles" /&gt;
  &lt;/template&gt;
&lt;/CodeEditorStudioTemplate&gt;</code></pre>
                  </div>
                </div>
              </div>
            </div>

            <!-- Components Section -->
            <div v-if="activeExample === 'components'" class="p-8">
              <h2 class="text-3xl font-bold mb-6">Component Examples</h2>
              
              <div class="space-y-8">
                <div class="border rounded-lg p-6">
                  <h3 class="text-xl font-semibold mb-4">StaticVerticalNavigationMenu</h3>
                  <p class="text-gray-600 mb-4">
                    A fixed navigation menu with predefined items. Use when navigation items don't change.
                  </p>
                  
                  <div class="bg-gray-100 rounded-lg p-4 mb-4">
                    <div class="bg-white rounded border p-4 max-w-xs">
                      <div class="text-xs font-semibold text-gray-500 uppercase mb-2">Navigation</div>
                      <div class="space-y-1">
                        <div class="flex items-center space-x-2 text-sm py-1 px-2 bg-blue-50 text-blue-700 rounded">
                          <span>📊</span>
                          <span>Dashboard</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm py-1 px-2 text-gray-600 hover:bg-gray-50 rounded cursor-pointer">
                          <span>📁</span>
                          <span>Projects</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm py-1 px-2 text-gray-600 hover:bg-gray-50 rounded cursor-pointer">
                          <span>📄</span>
                          <span>Files</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm py-1 px-2 text-gray-600 hover:bg-gray-50 rounded cursor-pointer">
                          <span>⚙️</span>
                          <span>Settings</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <button 
                    @click="showComponentExample = !showComponentExample"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                  >
                    {{ showComponentExample ? 'Hide' : 'Show' }} Code Example
                  </button>
                  
                  <div v-if="showComponentExample" class="mt-4 bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto">
                    <pre><code>&lt;!-- components/navigation/StaticVerticalNavigationMenu.vue --&gt;
&lt;template&gt;
  &lt;div class="space-y-2"&gt;
    &lt;h4 class="text-xs font-semibold text-muted-foreground uppercase"&gt;
      {{ menuTitle }}
    &lt;/h4&gt;
    &lt;div class="space-y-1"&gt;
      &lt;div 
        v-for="item in navigationItems" 
        :key="item.id"
        @click="handleItemClick(item)"
        class="flex items-center space-x-2 text-sm py-1 px-2 rounded cursor-pointer"
        :class="activeItem === item.id ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-50'"
      &gt;
        &lt;span&gt;{{ item.icon }}&lt;/span&gt;
        &lt;span&gt;{{ item.label }}&lt;/span&gt;
      &lt;/div&gt;
    &lt;/div&gt;
  &lt;/div&gt;
&lt;/template&gt;</code></pre>
                  </div>
                </div>

                <div class="border rounded-lg p-6">
                  <h3 class="text-xl font-semibold mb-4">RecentlyAccessedFilesList</h3>
                  <p class="text-gray-600 mb-4">
                    Shows a list of recently accessed files with timestamps. Much more descriptive than "RecentFiles".
                  </p>
                  
                  <div class="bg-gray-100 rounded-lg p-4">
                    <div class="bg-white rounded border p-4 max-w-sm">
                      <div class="text-sm font-medium mb-3">Recently Accessed Files</div>
                      <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                          <div class="flex items-center space-x-2">
                            <span>📄</span>
                            <span>App.vue</span>
                          </div>
                          <span class="text-xs text-gray-500">2m ago</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                          <div class="flex items-center space-x-2">
                            <span>📄</span>
                            <span>main.js</span>
                          </div>
                          <span class="text-xs text-gray-500">5m ago</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                          <div class="flex items-center space-x-2">
                            <span>📄</span>
                            <span>README.md</span>
                          </div>
                          <span class="text-xs text-gray-500">1h ago</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Usage Examples Section -->
            <div v-if="activeExample === 'usage'" class="p-8">
              <h2 class="text-3xl font-bold mb-6">Usage Examples</h2>
              
              <div class="space-y-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                  <h3 class="text-lg font-semibold text-blue-800 mb-3">How to Use This Structure</h3>
                  <div class="text-blue-700 space-y-2">
                    <p><strong>1. Start with a Layout:</strong> Choose the structural foundation</p>
                    <p><strong>2. Add a Template:</strong> If you need common functionality</p>
                    <p><strong>3. Fill with Components:</strong> Add specific UI pieces</p>
                    <p><strong>4. Create Pages:</strong> Combine everything for complete implementations</p>
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="border rounded-lg p-6">
                    <h4 class="font-semibold mb-3">For Content Editing</h4>
                    <div class="text-sm space-y-1">
                      <div>📐 <code>TwoColumnToggleContainerLayout</code></div>
                      <div>📄 <code>EditableContentTemplate</code></div>
                      <div>🧩 <code>StaticVerticalNavigationMenu</code></div>
                      <div>🧩 <code>RecentlyAccessedFilesList</code></div>
                    </div>
                  </div>
                  
                  <div class="border rounded-lg p-6">
                    <h4 class="font-semibold mb-3">For Development Work</h4>
                    <div class="text-sm space-y-1">
                      <div>📐 <code>ThreeColumnFlexibleLayout</code></div>
                      <div>📄 <code>CodeEditorStudioTemplate</code></div>
                      <div>🧩 <code>ProjectFileExplorer</code></div>
                      <div>🧩 <code>ToolPalette</code></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </main>
      </div>
    </div>
  </div>
</template>