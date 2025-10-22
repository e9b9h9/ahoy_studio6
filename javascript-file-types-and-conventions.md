# JavaScript File Types and Naming Conventions Guide

This comprehensive guide explains different types of JavaScript files, their purposes, naming conventions, and when to use each pattern in Vue.js applications.

---

## 1. Composables (`use` prefix)

### What They Are
Composables are **reusable functions** that encapsulate and reuse stateful logic using Vue's Composition API. They're Vue 3's equivalent to React hooks.

### Why "use" Prefix
The "use" prefix is a **naming convention** borrowed from React that:
- **Signals intent**: Immediately tells developers this contains reactive logic
- **Indicates patterns**: Shows this function likely returns reactive references
- **Follows standards**: Consistent with Vue 3 documentation and community practices
- **Enables tooling**: Some linters and tools recognize this pattern

### When to Use Composables
```javascript
// useCounter.js - Simple state management
import { ref, computed } from 'vue'

export function useCounter(initialValue = 0) {
    const count = ref(initialValue)
    
    const increment = () => count.value++
    const decrement = () => count.value--
    const reset = () => count.value = initialValue
    
    const isEven = computed(() => count.value % 2 === 0)
    
    return {
        count,
        increment,
        decrement,
        reset,
        isEven
    }
}

// useApi.js - API interaction logic
import { ref } from 'vue'
import axios from 'axios'

export function useApi(baseURL) {
    const loading = ref(false)
    const error = ref(null)
    const data = ref(null)
    
    const get = async (endpoint) => {
        loading.value = true
        error.value = null
        try {
            const response = await axios.get(`${baseURL}${endpoint}`)
            data.value = response.data
            return response.data
        } catch (err) {
            error.value = err.message
            throw err
        } finally {
            loading.value = false
        }
    }
    
    return { loading, error, data, get }
}

// useLocalStorage.js - Browser API integration
import { ref, watch } from 'vue'

export function useLocalStorage(key, defaultValue) {
    const storedValue = localStorage.getItem(key)
    const value = ref(storedValue ? JSON.parse(storedValue) : defaultValue)
    
    watch(value, (newValue) => {
        localStorage.setItem(key, JSON.stringify(newValue))
    }, { deep: true })
    
    return value
}
```

**Use composables for:**
- Reactive state that needs to be shared
- Complex logic that involves Vue reactivity
- Browser API interactions (localStorage, fetch, etc.)
- Form handling and validation
- Animation and UI state

---

## 2. Constants

### What They Are
Constants are **immutable values** that don't change during application runtime. They're used for configuration, enums, and fixed data.

### Naming Conventions
	```javascript
	// constants/ui.js
	export const COLORS = {
			PRIMARY: '#3b82f6',
			SECONDARY: '#64748b',
			SUCCESS: '#10b981',
			ERROR: '#ef4444',
			WARNING: '#f59e0b'
	}

	export const BREAKPOINTS = {
			MOBILE: 768,
			TABLET: 1024,
			DESKTOP: 1280
	}

	export const Z_INDEX = {
			DROPDOWN: 1000,
			MODAL: 1050,
			TOOLTIP: 1100,
			TOAST: 1200
	}

	// constants/api.js
	export const API_ENDPOINTS = {
			USERS: '/api/users',
			PROJECTS: '/api/projects',
			FILES: '/api/files'
	}

	export const HTTP_STATUS = {
			OK: 200,
			CREATED: 201,
			BAD_REQUEST: 400,
			UNAUTHORIZED: 401,
			NOT_FOUND: 404,
			SERVER_ERROR: 500
	}

	// constants/app.js
	export const APP_CONFIG = {
			MAX_FILE_SIZE: 10 * 1024 * 1024, // 10MB
			SUPPORTED_FILE_TYPES: ['.vue', '.js', '.ts', '.css'],
			DEBOUNCE_DELAY: 300,
			PAGINATION_SIZE: 20
	}

	export const FEATURE_FLAGS = {
			ENABLE_DARK_MODE: true,
			ENABLE_NOTIFICATIONS: true,
			ENABLE_FILE_UPLOAD: false
	}
	```

**Use constants for:**
- Configuration values
- Enum-like objects
- API endpoints
- Color schemes
- Fixed text/messages
- Magic numbers that need names

---

## 3. Stores (State Management)

### What They Are
Stores manage **global application state** using libraries like Pinia or Vuex. They centralize data that needs to be accessed by multiple components.

### Naming Conventions
	```javascript
	// stores/userStore.js
	import { defineStore } from 'pinia'

	export const useUserStore = defineStore('user', {
			state: () => ({
					currentUser: null,
					isAuthenticated: false,
					preferences: {}
			}),
			
			getters: {
					fullName: (state) => {
							return state.currentUser 
									? `${state.currentUser.firstName} ${state.currentUser.lastName}`
									: 'Guest'
					},
					hasPermission: (state) => (permission) => {
							return state.currentUser?.permissions?.includes(permission) || false
					}
			},
			
			actions: {
					async login(credentials) {
							const response = await api.post('/auth/login', credentials)
							this.currentUser = response.data.user
							this.isAuthenticated = true
					},
					
					logout() {
							this.currentUser = null
							this.isAuthenticated = false
							this.preferences = {}
					},
					
					updatePreferences(newPreferences) {
							this.preferences = { ...this.preferences, ...newPreferences }
					}
			}
	})

	// stores/codemateStore.js (from your project)
	export const useCodemateStore = defineStore('codemate', {
			state: () => ({
					topLevelFolders: [],
					selectedFolderId: null,
					selectedFile: null,
					watchedFiles: [],
					uninitializedFiles: []
			}),
			
			getters: {
					selectedFolder: (state) => {
							return state.topLevelFolders.find(folder => 
									folder.id === state.selectedFolderId
							) || null
					}
			},
			
			actions: {
					setSelectedFolderId(folderId) {
							this.selectedFolderId = folderId
					},
					
					async fetchTopLevelFolders() {
							const response = await api.get('/folders')
							this.topLevelFolders = response.data
					}
			}
	})
```

**Use stores for:**
- User authentication state
- Application-wide settings
- Data that multiple components need
- Complex state that needs persistence
- Shopping cart, selected items, etc.

---

## 4. Utils/Helpers

### What They Are
**Utils** are pure functions that perform specific tasks without side effects. **Helpers** are utility functions that assist with common operations.

### Why "Helpers" Suffix
The "helpers" suffix indicates:
- **Assistant functions**: They help other code accomplish tasks
- **Domain-specific**: Often grouped by what they help with
- **Pure functions**: Usually don't have side effects
- **Reusable**: Can be used in multiple places

```javascript
	// utils/dateHelpers.js
	export const formatDate = (date, format = 'YYYY-MM-DD') => {
			// Pure function that formats dates
			const d = new Date(date)
			return d.toLocaleDateString('en-US', {
					year: 'numeric',
					month: '2-digit',
					day: '2-digit'
			})
	}

	export const isToday = (date) => {
			const today = new Date()
			const targetDate = new Date(date)
			return today.toDateString() === targetDate.toDateString()
	}

	export const addDays = (date, days) => {
			const result = new Date(date)
			result.setDate(result.getDate() + days)
			return result
	}

	// utils/stringHelpers.js
	export const capitalize = (str) => {
			return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase()
	}

	export const slugify = (str) => {
			return str
					.toLowerCase()
					.replace(/[^\w\s-]/g, '')
					.replace(/[\s_-]+/g, '-')
					.replace(/^-+|-+$/g, '')
	}

	export const truncate = (str, length = 100, suffix = '...') => {
			return str.length <= length ? str : str.substring(0, length) + suffix
	}

	// utils/arrayHelpers.js
	export const groupBy = (array, key) => {
			return array.reduce((groups, item) => {
					const group = item[key]
					groups[group] = groups[group] || []
					groups[group].push(item)
					return groups
			}, {})
	}

	export const unique = (array) => {
			return [...new Set(array)]
	}

	export const sortBy = (array, key, direction = 'asc') => {
			return [...array].sort((a, b) => {
					const aVal = a[key]
					const bVal = b[key]
					if (direction === 'asc') {
							return aVal > bVal ? 1 : -1
					}
					return aVal < bVal ? 1 : -1
			})
	}

	// utils/fileHelpers.js (from your project)
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

**Difference between Utils and Helpers:**
- **Utils**: More general-purpose, often mathematical or algorithmic
- **Helpers**: More domain-specific, assist with particular tasks
- **Usage**: Often used interchangeably, but helpers are more contextual

---

## 5. Services Layer

### What Services Are
Services are **classes or modules** that encapsulate business logic and external integrations. They provide a **clean API** for components to interact with data and external systems.

### API Services
Handle HTTP requests and external API communication:

	```javascript
	// services/api/baseApi.js
	import axios from 'axios'

	class BaseApiService {
			constructor(baseURL) {
					this.client = axios.create({
							baseURL,
							timeout: 10000,
							headers: {
									'Content-Type': 'application/json'
							}
					})
					
					this.setupInterceptors()
			}
			
			setupInterceptors() {
					// Request interceptor
					this.client.interceptors.request.use(
							(config) => {
									const token = localStorage.getItem('auth_token')
									if (token) {
											config.headers.Authorization = `Bearer ${token}`
									}
									return config
							},
							(error) => Promise.reject(error)
					)
					
					// Response interceptor
					this.client.interceptors.response.use(
							(response) => response,
							(error) => {
									if (error.response?.status === 401) {
											// Handle unauthorized
											this.handleUnauthorized()
									}
									return Promise.reject(error)
							}
					)
			}
			
			async get(url, config = {}) {
					const response = await this.client.get(url, config)
					return response.data
			}
			
			async post(url, data = {}, config = {}) {
					const response = await this.client.post(url, data, config)
					return response.data
			}
			
			async put(url, data = {}, config = {}) {
					const response = await this.client.put(url, data, config)
					return response.data
			}
			
			async delete(url, config = {}) {
					const response = await this.client.delete(url, config)
					return response.data
			}
			
			handleUnauthorized() {
					localStorage.removeItem('auth_token')
					window.location.href = '/login'
			}
	}

	export const apiService = new BaseApiService('/api')

	// services/api/userApi.js
	import { apiService } from './baseApi.js'

	export class UserApiService {
			async getUsers(params = {}) {
					return apiService.get('/users', { params })
			}
			
			async getUser(id) {
					return apiService.get(`/users/${id}`)
			}
			
			async createUser(userData) {
					return apiService.post('/users', userData)
			}
			
			async updateUser(id, userData) {
					return apiService.put(`/users/${id}`, userData)
			}
			
			async deleteUser(id) {
					return apiService.delete(`/users/${id}`)
			}
			
			async uploadAvatar(userId, file) {
					const formData = new FormData()
					formData.append('avatar', file)
					
					return apiService.post(`/users/${userId}/avatar`, formData, {
							headers: { 'Content-Type': 'multipart/form-data' }
					})
			}
	}

	export const userApi = new UserApiService()
```

### Business Services
Handle business logic and domain-specific operations:

```javascript
	// services/business/userManager.js
	import { userApi } from '../api/userApi.js'
	import { validateEmail, validatePassword } from '../../utils/validators.js'

	export class UserManager {
			constructor() {
					this.cache = new Map()
					this.cacheTimeout = 5 * 60 * 1000 // 5 minutes
			}
			
			async createUser(userData) {
					// Business logic validation
					this.validateUserData(userData)
					
					// Check for existing user
					const existingUser = await this.findUserByEmail(userData.email)
					if (existingUser) {
							throw new Error('User with this email already exists')
					}
					
					// Create user with business rules
					const user = await userApi.createUser({
							...userData,
							role: userData.role || 'user',
							status: 'pending_verification',
							createdAt: new Date().toISOString()
					})
					
					// Send welcome email (business process)
					await this.sendWelcomeEmail(user)
					
					return user
			}
			
			async getUserWithCache(id) {
					const cacheKey = `user_${id}`
					
					if (this.cache.has(cacheKey)) {
							const cached = this.cache.get(cacheKey)
							if (Date.now() - cached.timestamp < this.cacheTimeout) {
									return cached.data
							}
					}
					
					const user = await userApi.getUser(id)
					this.cache.set(cacheKey, {
							data: user,
							timestamp: Date.now()
					})
					
					return user
			}
			
			validateUserData(userData) {
					if (!validateEmail(userData.email)) {
							throw new Error('Invalid email format')
					}
					
					if (!validatePassword(userData.password)) {
							throw new Error('Password must be at least 8 characters')
					}
					
					if (!userData.firstName?.trim()) {
							throw new Error('First name is required')
					}
			}
			
			async sendWelcomeEmail(user) {
					// Business process for sending welcome email
					console.log(`Sending welcome email to ${user.email}`)
			}
			
			async findUserByEmail(email) {
					try {
							const users = await userApi.getUsers({ email })
							return users.length > 0 ? users[0] : null
					} catch (error) {
							return null
					}
			}
	}

	export const userManager = new UserManager()
```

### Processors
Handle data transformation and complex operations:

	```javascript
	// services/processors/fileProcessor.js
	import { fileApi } from '../api/fileApi.js'

	export class FileProcessor {
			constructor() {
					this.processingQueue = []
					this.isProcessing = false
					this.maxConcurrent = 3
					this.currentProcessing = 0
			}
			
			async processFile(file, options = {}) {
					return new Promise((resolve, reject) => {
							this.processingQueue.push({
									file,
									options,
									resolve,
									reject,
									id: Date.now() + Math.random()
							})
							
							this.processNext()
					})
			}
			
			async processNext() {
					if (this.currentProcessing >= this.maxConcurrent || this.processingQueue.length === 0) {
							return
					}
					
					const task = this.processingQueue.shift()
					this.currentProcessing++
					
					try {
							const result = await this.executeProcessing(task.file, task.options)
							task.resolve(result)
					} catch (error) {
							task.reject(error)
					} finally {
							this.currentProcessing--
							this.processNext()
					}
			}
			
			async executeProcessing(file, options) {
					// Validate file
					this.validateFile(file)
					
					// Extract metadata
					const metadata = await this.extractMetadata(file)
					
					// Process based on file type
					let processedData
					if (file.type.startsWith('image/')) {
							processedData = await this.processImage(file, options)
					} else if (file.type === 'text/plain') {
							processedData = await this.processText(file, options)
					} else {
							processedData = await this.processGeneric(file, options)
					}
					
					// Save to backend
					const result = await fileApi.saveProcessedFile({
							originalFile: file,
							metadata,
							processedData,
							options
					})
					
					return result
			}
			
			validateFile(file) {
					const maxSize = 10 * 1024 * 1024 // 10MB
					if (file.size > maxSize) {
							throw new Error('File too large')
					}
					
					const allowedTypes = ['image/', 'text/', 'application/json']
					if (!allowedTypes.some(type => file.type.startsWith(type))) {
							throw new Error('File type not supported')
					}
			}
			
			async extractMetadata(file) {
					return {
							name: file.name,
							size: file.size,
							type: file.type,
							lastModified: new Date(file.lastModified),
							checksum: await this.calculateChecksum(file)
					}
			}
			
			async calculateChecksum(file) {
					const arrayBuffer = await file.arrayBuffer()
					const hashBuffer = await crypto.subtle.digest('SHA-256', arrayBuffer)
					const hashArray = Array.from(new Uint8Array(hashBuffer))
					return hashArray.map(b => b.toString(16).padStart(2, '0')).join('')
			}
			
			async processImage(file, options) {
					// Image processing logic
					return { type: 'image', processed: true }
			}
			
			async processText(file, options) {
					const text = await file.text()
					return {
							type: 'text',
							lineCount: text.split('\n').length,
							wordCount: text.split(/\s+/).length,
							charCount: text.length
					}
			}
			
			async processGeneric(file, options) {
					return { type: 'generic', processed: true }
			}
	}

	export const fileProcessor = new FileProcessor()
	```

---

## 6. Types (TypeScript)

	### What They Are
	Types define the **structure and shape of data** in TypeScript applications. They provide compile-time type checking and better IDE support.

	```typescript
	// types/user.ts
	export interface User {
			id: number
			email: string
			firstName: string
			lastName: string
			role: UserRole
			status: UserStatus
			createdAt: string
			updatedAt: string
			preferences?: UserPreferences
	}

	export type UserRole = 'admin' | 'user' | 'moderator'
	export type UserStatus = 'active' | 'inactive' | 'pending_verification' | 'suspended'

	export interface UserPreferences {
			theme: 'light' | 'dark' | 'auto'
			language: string
			notifications: {
					email: boolean
					push: boolean
					inApp: boolean
			}
			privacy: {
					profileVisible: boolean
					showEmail: boolean
			}
	}

	export interface CreateUserRequest {
			email: string
			firstName: string
			lastName: string
			password: string
			role?: UserRole
	}

	export interface UpdateUserRequest {
			firstName?: string
			lastName?: string
			role?: UserRole
			status?: UserStatus
			preferences?: Partial<UserPreferences>
	}

	// types/api.ts
	export interface ApiResponse<T = any> {
			data: T
			message?: string
			status: number
			timestamp: string
	}

	export interface PaginatedResponse<T> extends ApiResponse<T[]> {
			pagination: {
					page: number
					limit: number
					total: number
					totalPages: number
			}
	}

	export interface ApiError {
			message: string
			status: number
			code?: string
			details?: Record<string, any>
	}

	// types/file.ts
	export interface FileInfo {
			id: string
			name: string
			size: number
			type: string
			path: string
			checksum: string
			metadata: FileMetadata
			createdAt: string
			updatedAt: string
	}

	export interface FileMetadata {
			dimensions?: {
					width: number
					height: number
			}
			duration?: number // for video/audio
			encoding?: string
			lineCount?: number // for text files
			wordCount?: number
	}

	export type FileStatus = 'uploading' | 'processing' | 'ready' | 'error'

	export interface FileProcessingOptions {
			resize?: {
					width: number
					height: number
					maintain_aspect_ratio: boolean
			}
			compress?: {
					quality: number
			}
			format?: string
	}
	```

	---

## 7. Modals (UI Patterns)

### What Modals Are
**Modals** are **overlay windows** that appear on top of the main content. They're used for dialogs, forms, confirmations, and temporary content that requires user attention.

	```javascript
	// composables/useModal.js
	import { ref, computed } from 'vue'

	export function useModal() {
			const isOpen = ref(false)
			const modalData = ref(null)
			
			const open = (data = null) => {
					modalData.value = data
					isOpen.value = true
					document.body.style.overflow = 'hidden' // Prevent background scroll
			}
			
			const close = () => {
					isOpen.value = false
					modalData.value = null
					document.body.style.overflow = ''
			}
			
			const toggle = () => {
					isOpen.value ? close() : open()
			}
			
			return {
					isOpen,
					modalData,
					open,
					close,
					toggle
			}
	}

	// composables/useConfirmModal.js
	import { ref } from 'vue'

	export function useConfirmModal() {
			const isOpen = ref(false)
			const config = ref({
					title: '',
					message: '',
					confirmText: 'Confirm',
					cancelText: 'Cancel',
					variant: 'default' // 'danger', 'warning', etc.
			})
			
			let resolvePromise = null
			
			const confirm = (options = {}) => {
					config.value = { ...config.value, ...options }
					isOpen.value = true
					
					return new Promise((resolve) => {
							resolvePromise = resolve
					})
			}
			
			const handleConfirm = () => {
					isOpen.value = false
					if (resolvePromise) resolvePromise(true)
			}
			
			const handleCancel = () => {
					isOpen.value = false
					if (resolvePromise) resolvePromise(false)
			}
			
			return {
					isOpen,
					config,
					confirm,
					handleConfirm,
					handleCancel
			}
	}

	// stores/modalStore.js
	import { defineStore } from 'pinia'

	export const useModalStore = defineStore('modal', {
			state: () => ({
					modals: [], // Stack of open modals
					backdrop: true,
					closeOnEsc: true
			}),
			
			getters: {
					hasOpenModals: (state) => state.modals.length > 0,
					currentModal: (state) => state.modals[state.modals.length - 1] || null
			},
			
			actions: {
					openModal(modal) {
							this.modals.push({
									id: Date.now(),
									...modal,
									timestamp: new Date()
							})
					},
					
					closeModal(id) {
							if (id) {
									this.modals = this.modals.filter(modal => modal.id !== id)
							} else {
									this.modals.pop() // Close topmost modal
							}
					},
					
					closeAllModals() {
							this.modals = []
					}
			}
	})
```

**Modal Examples:**
- **Confirmation dialogs**: "Are you sure you want to delete?"
- **Form modals**: Edit user, create project
- **Image galleries**: Photo viewers
- **Settings panels**: App configuration
- **Error dialogs**: Show error messages

---

## 8. Router (Frontend Routing)

### What Frontend Routing Is
In **Single Page Applications (SPAs)**, the router handles **client-side navigation** without full page reloads. This is **different** from Laravel's `web.php` which handles **server-side routing**.

	```javascript
	// router/index.js
	import { createRouter, createWebHistory } from 'vue-router'
	import { useUserStore } from '../stores/userStore.js'

	// Lazy load components
	const Dashboard = () => import('../pages/Dashboard.vue')
	const ProjectsPage = () => import('../pages/ProjectsPage.vue')
	const SettingsPage = () => import('../pages/SettingsPage.vue')
	const LoginPage = () => import('../pages/auth/LoginPage.vue')

	const routes = [
			{
					path: '/',
					name: 'home',
					component: Dashboard,
					meta: { requiresAuth: true }
			},
			{
					path: '/projects',
					name: 'projects',
					component: ProjectsPage,
					meta: { requiresAuth: true }
			},
			{
					path: '/projects/:id',
					name: 'project-detail',
					component: () => import('../pages/ProjectDetailPage.vue'),
					props: true,
					meta: { requiresAuth: true }
			},
			{
					path: '/settings',
					name: 'settings',
					component: SettingsPage,
					meta: { requiresAuth: true },
					children: [
							{
									path: 'profile',
									component: () => import('../pages/settings/ProfilePage.vue')
							},
							{
									path: 'preferences',
									component: () => import('../pages/settings/PreferencesPage.vue')
							}
					]
			},
			{
					path: '/login',
					name: 'login',
					component: LoginPage,
					meta: { requiresGuest: true }
			},
			{
					path: '/:pathMatch(.*)*',
					name: 'not-found',
					component: () => import('../pages/NotFoundPage.vue')
			}
	]

	const router = createRouter({
			history: createWebHistory(),
			routes
	})

	// router/guards.js
	export function setupRouterGuards(router) {
			// Global before guard
			router.beforeEach(async (to, from, next) => {
					const userStore = useUserStore()
					
					// Check if route requires authentication
					if (to.meta.requiresAuth && !userStore.isAuthenticated) {
							next({ name: 'login', query: { redirect: to.fullPath } })
							return
					}
					
					// Check if route requires guest (not authenticated)
					if (to.meta.requiresGuest && userStore.isAuthenticated) {
							next({ name: 'home' })
							return
					}
					
					// Check permissions
					if (to.meta.requiredPermissions) {
							const hasPermission = to.meta.requiredPermissions.every(
									permission => userStore.hasPermission(permission)
							)
							
							if (!hasPermission) {
									next({ name: 'unauthorized' })
									return
							}
					}
					
					next()
			})
			
			// Global after guard
			router.afterEach((to, from) => {
					// Update page title
					document.title = to.meta.title || 'My App'
					
					// Track page views
					if (window.gtag) {
							window.gtag('config', 'GA_TRACKING_ID', {
									page_path: to.path
							})
					}
			})
	}

	export default router
```

### Router vs Laravel Routes

**Laravel Routes (`web.php`):**
- **Server-side**: Handle HTTP requests on the server
- **Full page loads**: Each route typically loads a new page
- **SEO friendly**: Search engines can crawl easily
- **Traditional web apps**: Multi-page applications

**Frontend Router:**
- **Client-side**: Handle navigation in the browser
- **No page reloads**: Fast transitions between "pages"
- **SPA experience**: Single Page Application behavior
- **Requires SSR for SEO**: Server-side rendering for search engines

---

## File Naming Convention Summary

### Prefixes and Suffixes

| Pattern | Purpose | Example |
|---------|---------|---------|
| `use*` | Composables with reactive logic | `useCounter.js`, `useApi.js` |
| `*Store` | State management | `userStore.js`, `codemateStore.js` |
| `*Service` | Business logic classes | `userService.js`, `fileService.js` |
| `*Api` | API communication | `userApi.js`, `projectApi.js` |
| `*Helpers` | Domain-specific utilities | `dateHelpers.js`, `fileHelpers.js` |
| `*Utils` | General utilities | `stringUtils.js`, `arrayUtils.js` |
| `*Manager` | Complex business operations | `userManager.js`, `projectManager.js` |
| `*Processor` | Data transformation | `fileProcessor.js`, `imageProcessor.js` |
| `*Modal` | Modal-related logic | `confirmModal.js`, `formModal.js` |

### File Organization Best Practices

1. **Group by purpose**: Store similar files together
2. **Use index.js**: For clean imports and exports
3. **Consistent naming**: Follow established patterns
4. **Clear separation**: Keep different concerns in different files
5. **Progressive complexity**: Simple → complex organization
6. **Domain boundaries**: Separate business domains clearly

This structure helps maintain clean, scalable codebases where developers can quickly understand file purposes and find what they need.