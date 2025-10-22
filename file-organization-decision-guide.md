# File Organization Decision Guide

This guide provides a **decision tree approach** to determine where files should go and what they should be named. Follow the questions in order to systematically organize your codebase.

---

## Quick Decision Flowchart

```
New File? → Ask: "What does this file DO?"
    ├── Shows UI/Layout → COMPONENT
    ├── Manages State → STORE  
    ├── Contains Logic → SERVICE/COMPOSABLE/UTILS
    ├── Defines Structure → TYPES
    └── Fixed Values → CONSTANTS
```

---

## Primary Classification Questions

### 1. **What is the primary purpose of this file?**

| Purpose | Category | Go To Section |
|---------|----------|---------------|
| Displays UI elements | **Component** | [Component Decision Tree](#component-decision-tree) |
| Manages global state | **Store** | [Store Classification](#store-classification) |
| Contains reusable logic | **Logic** | [Logic Decision Tree](#logic-decision-tree) |
| Defines data structure | **Types** | [Types Classification](#types-classification) |
| Stores fixed values | **Constants** | [Constants Classification](#constants-classification) |

---

## Component Decision Tree

### Primary Question: "What level of complexity is this component?"

#### Level 1: Basic Building Blocks
**Questions to ask:**
- ✅ Can this be used anywhere without modification?
- ✅ Does it have no business logic?
- ✅ Is it purely presentational?

**Examples:** Button, Input, Card, Icon
**Naming:** Descriptive noun (Button.vue, Card.vue)
**Location:** `/primitives/` or `/ui/atoms/`

#### Level 2: Content Components  
**Questions to ask:**
- ✅ Does this display specific content?
- ✅ Does it have minimal props/logic?
- ✅ Is it used in multiple places?

**Examples:** HeaderTools, NavigationMenu, FileList
**Naming:** Descriptive purpose (HeaderTools.vue, NavigationMenu.vue)
**Location:** `/components/` or `/molecules/`

#### Level 3: Domain-Specific Components
**Questions to ask:**
- ✅ Is this tied to a specific feature/domain?
- ❌ Would other features use this exact component?
- ✅ Does it contain business logic?

**Examples:** CodemateFileTree, TestingWorkspace, UserProfile
**Naming:** `[Domain][Purpose]` (CodemateFileTree.vue, TestingWorkspace.vue)
**Location:** `/features/[domain]/components/`

#### Level 4: Layout Components
**Questions to ask:**
- ✅ Does this define page structure?
- ✅ Does it use slots for content?
- ❌ Does it contain actual content?

**Examples:** MainLayout, ThreeColumnLayout, SidebarLayout
**Naming:** `[Purpose]Layout` (MainLayout.vue, ThreeColumnLayout.vue)
**Location:** `/layouts/` or `/features/[domain]/layouts/`

#### Level 5: Composite/Organism Components
**Questions to ask:**
- ✅ Does this combine multiple components?
- ✅ Does it manage complex state?
- ✅ Is it a complete functional unit?

**Examples:** TestingWorkspace, ProjectExplorer, UserDashboard
**Naming:** `[Domain][Function]` (TestingWorkspace.vue, ProjectExplorer.vue)
**Location:** `/composites/` or `/features/[domain]/organisms/`

#### Level 6: Page Components
**Questions to ask:**
- ✅ Is this a complete page/route?
- ✅ Does it represent a URL endpoint?
- ✅ Is it the top-level component for a view?

**Examples:** DashboardPage, SettingsPage, ProjectDetailPage
**Naming:** `[Purpose]Page` (DashboardPage.vue, SettingsPage.vue)
**Location:** `/pages/`

---

## Logic Decision Tree

### Primary Question: "What type of logic does this contain?"

#### Composables (`use*`)
**Questions to ask:**
- ✅ Does this use Vue reactivity (ref, computed, watch)?
- ✅ Will components call this to get reactive state?
- ✅ Does this manage component lifecycle?

**Examples:** useCounter, useApi, useFileUpload, useModal
**Naming:** `use[Purpose]` (useCounter.js, useFileUpload.js)
**Location:** `/composables/` or `/features/[domain]/composables/`

**Specific Use Cases:**
```javascript
// ✅ COMPOSABLE - Has reactive state
export function useCounter() {
    const count = ref(0) // Vue reactivity
    const increment = () => count.value++
    return { count, increment }
}

// ❌ NOT COMPOSABLE - Pure function
export function addNumbers(a, b) {
    return a + b // No reactivity
}
```

#### Services
**Questions to ask:**
- ✅ Does this handle business logic?
- ✅ Does it interact with external APIs?
- ✅ Is it a class or complex object?

**Sub-categories:**

##### API Services (`*Api`)
**Questions:**
- ✅ Does this make HTTP requests?
- ✅ Is it focused on one API endpoint/resource?

**Examples:** userApi, projectApi, fileApi
**Naming:** `[resource]Api` (userApi.js, projectApi.js)
**Location:** `/services/api/`

##### Business Services (`*Service`, `*Manager`)
**Questions:**
- ✅ Does this contain business rules?
- ✅ Does it orchestrate multiple operations?
- ✅ Does it handle complex workflows?

**Examples:** UserManager, ProjectService, FileProcessor
**Naming:** `[domain]Service` or `[domain]Manager`
**Location:** `/services/business/`

##### Processors (`*Processor`)
**Questions:**
- ✅ Does this transform/process data?
- ✅ Does it handle queues or batch operations?
- ✅ Is it focused on data manipulation?

**Examples:** FileProcessor, ImageProcessor, DataProcessor
**Naming:** `[type]Processor` (fileProcessor.js)
**Location:** `/services/processors/`

#### Utils/Helpers
**Questions to ask:**
- ✅ Are these pure functions?
- ❌ Do they use Vue reactivity?
- ✅ Can they be used anywhere?

**Sub-categories:**

##### General Utils (`*Utils`)
**Questions:**
- ✅ Are these generic, reusable functions?
- ✅ Could they be used in any project?

**Examples:** stringUtils, arrayUtils, dateUtils
**Naming:** `[type]Utils` (stringUtils.js, arrayUtils.js)
**Location:** `/utils/`

##### Domain Helpers (`*Helpers`)
**Questions:**
- ✅ Are these specific to a domain/feature?
- ✅ Do they help with specific tasks?

**Examples:** fileHelpers, formHelpers, treeHelpers
**Naming:** `[domain]Helpers` (fileHelpers.js, formHelpers.js)
**Location:** `/utils/` or `/features/[domain]/utils/`

---

## Store Classification

### Questions to ask:
- ✅ Does this manage state used by multiple components?
- ✅ Does this state need to persist across route changes?
- ✅ Is this global application state?

### Store Types:

#### App-Wide Stores
**Examples:** userStore, appStore, uiStore
**Naming:** `[purpose]Store` (userStore.js, appStore.js)
**Location:** `/stores/`

#### Feature Stores  
**Examples:** codemateStore, testingStore, settingsStore
**Naming:** `[feature]Store` (codemateStore.js, testingStore.js)
**Location:** `/features/[domain]/stores/`

#### UI Stores
**Examples:** modalStore, toastStore, sidebarStore
**Naming:** `[ui-element]Store` (modalStore.js, toastStore.js)
**Location:** `/stores/ui/`

---

## Types Classification

### Questions to ask:
- ✅ Does this define data structure?
- ✅ Is this for type definitions or schemas?
- ✅ Does this describe API interfaces or data models?

### Type Categories:

#### Domain Types
**Examples:** User, Project, File interfaces/schemas
**Naming:** `[domain].js` (user.js, project.js, file.js)
**Location:** `/types/` or `/features/[domain]/types/`

#### API Types
**Examples:** API responses, request payloads, endpoint schemas
**Naming:** `api.js`, `[service]Api.js`
**Location:** `/types/api/`

#### UI Types
**Examples:** Component prop schemas, form validation schemas
**Naming:** `ui.js`, `components.js`
**Location:** `/types/ui/`

---

## Constants Classification

### Questions to ask:
- ✅ Are these values that never change?
- ✅ Are these configuration options?
- ✅ Do multiple files need these values?

### Constant Types:

#### App Constants
**Examples:** API_BASE_URL, APP_NAME, VERSION
**Naming:** `app.js`, `config.js`
**Location:** `/constants/`

#### UI Constants
**Examples:** Colors, breakpoints, z-index values
**Naming:** `ui.js`, `theme.js`, `colors.js`
**Location:** `/constants/ui/`

#### Feature Constants
**Examples:** File size limits, supported formats
**Naming:** `[feature].js` (codemate.js, testing.js)
**Location:** `/features/[domain]/constants/`

---

## Decision Questions Checklist

### For Every New File, Ask:

#### **Purpose Questions:**
1. **What does this file DO?** (Display, manage, process, define, store)
2. **Who will use this?** (Components, other files, external systems)
3. **How complex is it?** (Simple function, complex class, reactive logic)

#### **Scope Questions:**
4. **Is this specific to one feature?** (Feature-specific vs shared)
5. **Will other domains use this?** (Reusability assessment)
6. **Does this handle business logic?** (Business vs presentation logic)

#### **Technical Questions:**
7. **Does this use Vue reactivity?** (Composable vs utility)
8. **Does this manage state?** (Store vs stateless)
9. **Is this pure data/config?** (Constants vs logic)

#### **Organization Questions:**
10. **Where would a developer expect to find this?** (Intuitive location)
11. **What would they search for?** (Naming convention)
12. **How does this relate to existing files?** (Consistency)

---

## Quick Reference Naming Patterns

### Prefixes
| Prefix | Meaning | Example |
|--------|---------|---------|
| `use` | Vue composable with reactivity | `useCounter.js` |
| `is` | Boolean checker function | `isValidEmail.js` |
| `get` | Data retrieval function | `getUsers.js` |
| `create` | Factory/creation function | `createLogger.js` |
| `handle` | Event handler function | `handleSubmit.js` |

### Suffixes
| Suffix | Meaning | Example |
|--------|---------|---------|
| `Store` | State management | `userStore.js` |
| `Service` | Business logic class | `userService.js` |
| `Api` | HTTP/API communication | `userApi.js` |
| `Manager` | Complex business operations | `userManager.js` |
| `Processor` | Data processing | `fileProcessor.js` |
| `Helper` | Domain-specific utilities | `fileHelper.js` |
| `Utils` | General utilities | `stringUtils.js` |
| `Types` | Data structure definitions | `userTypes.js` |
| `Constants` | Fixed values | `appConstants.js` |
| `Modal` | Modal-related logic | `confirmModal.js` |
| `Layout` | Layout component | `MainLayout.vue` |
| `Page` | Page component | `DashboardPage.vue` |

### Component Naming
| Pattern | Purpose | Example |
|---------|---------|---------|
| `[Noun]` | Basic UI element | `Button.vue`, `Card.vue` |
| `[Adjective][Noun]` | Variant of basic element | `PrimaryButton.vue` |
| `[Domain][Noun]` | Feature-specific component | `CodemateFileTree.vue` |
| `[Noun][Layout/Modal/Form]` | Component type suffix | `UserModal.vue`, `ProjectForm.vue` |

---

## Common Mistakes to Avoid

### ❌ Wrong Patterns:
- **Mixed purposes**: File that does multiple unrelated things
- **Unclear naming**: Generic names like `helper.js`, `component.vue`
- **Wrong location**: Business logic in components, UI logic in services
- **Inconsistent naming**: Some files follow pattern, others don't

### ✅ Right Patterns:
- **Single responsibility**: Each file has one clear purpose
- **Predictable naming**: Developers can guess filename from purpose
- **Logical grouping**: Related files are near each other
- **Consistent conventions**: All files follow the same patterns

---

## Quick Decision Examples

### Example 1: "I have a function that formats file sizes"
**Questions:**
- Does it use Vue reactivity? ❌
- Is it specific to one feature? ❌ (files used everywhere)
- Is it a pure function? ✅

**Decision:** `utils/fileHelpers.js` → `formatFileSize()`

### Example 2: "I have reactive state for managing file uploads"
**Questions:**
- Does it use Vue reactivity? ✅
- Will components use this? ✅
- Does it manage component state? ✅

**Decision:** `composables/useFileUpload.js`

### Example 3: "I have a component that shows a list of projects"
**Questions:**
- Is it tied to one domain? ✅ (projects)
- Does it contain business logic? ✅
- Is it reusable outside this domain? ❌

**Decision:** `features/projects/components/ProjectList.vue`

### Example 4: "I have API calls for user management"
**Questions:**
- Does it make HTTP requests? ✅
- Is it focused on one resource? ✅ (users)
- Is it a class/service? ✅

**Decision:** `services/api/userApi.js`

This decision tree helps you systematically determine the right place and name for any file in your Vue.js application.