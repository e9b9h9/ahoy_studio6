# JavaScript Types and Schema Definitions

This guide shows how to define data structures, schemas, and type definitions using **JavaScript** instead of TypeScript, with practical examples for Vue.js applications.

---

## Why JavaScript for Types?

### Benefits:
- **No compilation step** - Works directly in browser/Node.js
- **Runtime validation** - Can validate data at runtime
- **Simpler setup** - No TypeScript configuration needed
- **Gradual adoption** - Add type definitions incrementally
- **Dynamic flexibility** - Can modify schemas at runtime

---

## 1. Object Schema Definitions

### Basic Schema Pattern
```javascript
// types/user.js
export const UserSchema = {
    id: 'number',
    email: 'string',
    firstName: 'string',
    lastName: 'string',
    role: ['admin', 'user', 'moderator'], // enum
    status: ['active', 'inactive', 'pending_verification'],
    createdAt: 'string',
    updatedAt: 'string',
    preferences: 'object' // nested schema
}

export const UserPreferencesSchema = {
    theme: ['light', 'dark', 'auto'],
    language: 'string',
    notifications: {
        email: 'boolean',
        push: 'boolean',
        inApp: 'boolean'
    },
    privacy: {
        profileVisible: 'boolean',
        showEmail: 'boolean'
    }
}

// Request/Response schemas
export const CreateUserRequestSchema = {
    email: 'string',
    firstName: 'string',
    lastName: 'string',
    password: 'string',
    role: ['admin', 'user', 'moderator'] // optional with default
}

export const UpdateUserRequestSchema = {
    firstName: 'string?', // optional field
    lastName: 'string?',
    role: ['admin', 'user', 'moderator', null],
    preferences: 'object?'
}
```

### Advanced Schema with Validation
```javascript
// types/file.js
export const FileSchema = {
    id: {
        type: 'string',
        required: true,
        pattern: /^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/
    },
    name: {
        type: 'string',
        required: true,
        minLength: 1,
        maxLength: 255
    },
    size: {
        type: 'number',
        required: true,
        min: 0,
        max: 10 * 1024 * 1024 // 10MB
    },
    type: {
        type: 'string',
        required: true,
        enum: ['image/jpeg', 'image/png', 'text/plain', 'application/pdf']
    },
    path: {
        type: 'string',
        required: true,
        pattern: /^\/[a-zA-Z0-9\-_\/\.]+$/
    },
    metadata: {
        type: 'object',
        schema: 'FileMetadataSchema'
    },
    createdAt: {
        type: 'string',
        required: true,
        format: 'iso-date'
    }
}

export const FileMetadataSchema = {
    dimensions: {
        type: 'object?',
        schema: {
            width: 'number',
            height: 'number'
        }
    },
    duration: 'number?', // for video/audio
    encoding: 'string?',
    lineCount: 'number?', // for text files
    wordCount: 'number?'
}

export const FileStatusEnum = ['uploading', 'processing', 'ready', 'error']

export const FileProcessingOptionsSchema = {
    resize: {
        type: 'object?',
        schema: {
            width: 'number',
            height: 'number',
            maintainAspectRatio: 'boolean'
        }
    },
    compress: {
        type: 'object?',
        schema: {
            quality: {
                type: 'number',
                min: 0,
                max: 100
            }
        }
    },
    format: {
        type: 'string?',
        enum: ['jpeg', 'png', 'webp']
    }
}
```

---

## 2. API Schema Definitions

```javascript
// types/api.js
export const ApiResponseSchema = {
    data: 'any',
    message: 'string?',
    status: 'number',
    timestamp: 'string'
}

export const PaginatedResponseSchema = {
    data: 'array',
    message: 'string?',
    status: 'number',
    timestamp: 'string',
    pagination: {
        type: 'object',
        schema: {
            page: 'number',
            limit: 'number',
            total: 'number',
            totalPages: 'number'
        }
    }
}

export const ApiErrorSchema = {
    message: 'string',
    status: 'number',
    code: 'string?',
    details: 'object?'
}

// Endpoint schemas
export const EndpointSchemas = {
    'GET /api/users': {
        query: {
            page: 'number?',
            limit: 'number?',
            search: 'string?',
            role: ['admin', 'user', 'moderator', null]
        },
        response: {
            ...PaginatedResponseSchema,
            data: 'array[UserSchema]'
        }
    },
    
    'POST /api/users': {
        body: CreateUserRequestSchema,
        response: {
            ...ApiResponseSchema,
            data: 'UserSchema'
        }
    },
    
    'PUT /api/users/:id': {
        params: {
            id: 'number'
        },
        body: UpdateUserRequestSchema,
        response: {
            ...ApiResponseSchema,
            data: 'UserSchema'
        }
    }
}
```

---

## 3. Component Prop Schemas

```javascript
// types/components.js
export const ButtonPropsSchema = {
    variant: {
        type: 'string',
        enum: ['primary', 'secondary', 'danger', 'outline'],
        default: 'primary'
    },
    size: {
        type: 'string',
        enum: ['small', 'medium', 'large'],
        default: 'medium'
    },
    disabled: {
        type: 'boolean',
        default: false
    },
    loading: {
        type: 'boolean',
        default: false
    },
    icon: 'string?',
    onClick: 'function?'
}

export const ModalPropsSchema = {
    isOpen: {
        type: 'boolean',
        required: true
    },
    title: 'string?',
    size: {
        type: 'string',
        enum: ['small', 'medium', 'large', 'fullscreen'],
        default: 'medium'
    },
    closable: {
        type: 'boolean',
        default: true
    },
    backdrop: {
        type: ['boolean', 'string'],
        enum: [true, false, 'static'],
        default: true
    },
    onClose: 'function?'
}

export const FileUploadPropsSchema = {
    accept: {
        type: 'string',
        default: '*/*'
    },
    multiple: {
        type: 'boolean',
        default: false
    },
    maxSize: {
        type: 'number',
        default: 10 * 1024 * 1024 // 10MB
    },
    maxFiles: {
        type: 'number?'
    },
    onUpload: {
        type: 'function',
        required: true
    },
    onError: 'function?',
    onProgress: 'function?'
}
```

---

## 4. Form Schema Definitions

```javascript
// types/forms.js
export const LoginFormSchema = {
    email: {
        type: 'string',
        required: true,
        validation: {
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            message: 'Please enter a valid email address'
        }
    },
    password: {
        type: 'string',
        required: true,
        validation: {
            minLength: 8,
            message: 'Password must be at least 8 characters'
        }
    },
    rememberMe: {
        type: 'boolean',
        default: false
    }
}

export const ProjectFormSchema = {
    name: {
        type: 'string',
        required: true,
        validation: {
            minLength: 2,
            maxLength: 100,
            pattern: /^[a-zA-Z0-9\s\-_]+$/,
            message: 'Project name must be 2-100 characters, letters, numbers, spaces, hyphens, and underscores only'
        }
    },
    description: {
        type: 'string',
        validation: {
            maxLength: 500,
            message: 'Description cannot exceed 500 characters'
        }
    },
    type: {
        type: 'string',
        required: true,
        enum: ['web', 'mobile', 'desktop', 'api'],
        validation: {
            message: 'Please select a valid project type'
        }
    },
    technologies: {
        type: 'array',
        validation: {
            minItems: 1,
            maxItems: 10,
            message: 'Please select 1-10 technologies'
        }
    },
    isPublic: {
        type: 'boolean',
        default: false
    }
}
```

---

## 5. Schema Validation Functions

```javascript
// utils/schemaValidator.js
export class SchemaValidator {
    static validate(data, schema) {
        const errors = []
        
        for (const [key, definition] of Object.entries(schema)) {
            const value = data[key]
            const error = this.validateField(key, value, definition)
            if (error) errors.push(error)
        }
        
        return {
            isValid: errors.length === 0,
            errors
        }
    }
    
    static validateField(key, value, definition) {
        // Handle simple type strings
        if (typeof definition === 'string') {
            return this.validateSimpleType(key, value, definition)
        }
        
        // Handle array enums
        if (Array.isArray(definition)) {
            if (!definition.includes(value)) {
                return `${key} must be one of: ${definition.join(', ')}`
            }
            return null
        }
        
        // Handle object definitions
        if (typeof definition === 'object') {
            return this.validateObjectDefinition(key, value, definition)
        }
        
        return null
    }
    
    static validateSimpleType(key, value, type) {
        const isOptional = type.endsWith('?')
        const baseType = isOptional ? type.slice(0, -1) : type
        
        if (isOptional && (value === undefined || value === null)) {
            return null
        }
        
        if (!isOptional && (value === undefined || value === null)) {
            return `${key} is required`
        }
        
        const actualType = Array.isArray(value) ? 'array' : typeof value
        if (actualType !== baseType) {
            return `${key} must be of type ${baseType}, got ${actualType}`
        }
        
        return null
    }
    
    static validateObjectDefinition(key, value, definition) {
        if (definition.required && (value === undefined || value === null)) {
            return `${key} is required`
        }
        
        if (value === undefined || value === null) {
            return null
        }
        
        // Type validation
        if (definition.type) {
            const error = this.validateSimpleType(key, value, definition.type)
            if (error) return error
        }
        
        // Enum validation
        if (definition.enum && !definition.enum.includes(value)) {
            return `${key} must be one of: ${definition.enum.join(', ')}`
        }
        
        // Pattern validation
        if (definition.pattern && typeof value === 'string') {
            if (!definition.pattern.test(value)) {
                return definition.message || `${key} format is invalid`
            }
        }
        
        // Length validation
        if (definition.minLength && value.length < definition.minLength) {
            return `${key} must be at least ${definition.minLength} characters`
        }
        
        if (definition.maxLength && value.length > definition.maxLength) {
            return `${key} cannot exceed ${definition.maxLength} characters`
        }
        
        // Number validation
        if (definition.min && value < definition.min) {
            return `${key} must be at least ${definition.min}`
        }
        
        if (definition.max && value > definition.max) {
            return `${key} cannot exceed ${definition.max}`
        }
        
        // Nested object validation
        if (definition.schema && typeof value === 'object') {
            const nestedResult = this.validate(value, definition.schema)
            if (!nestedResult.isValid) {
                return `${key} validation failed: ${nestedResult.errors.join(', ')}`
            }
        }
        
        return null
    }
}

// Usage examples
export function validateUser(userData) {
    return SchemaValidator.validate(userData, UserSchema)
}

export function validateFile(fileData) {
    return SchemaValidator.validate(fileData, FileSchema)
}

export function validateCreateUserRequest(requestData) {
    return SchemaValidator.validate(requestData, CreateUserRequestSchema)
}
```

---

## 6. Schema Usage in Components

```javascript
// composables/useFormValidation.js
import { ref, computed } from 'vue'
import { SchemaValidator } from '../utils/schemaValidator.js'

export function useFormValidation(schema) {
    const formData = ref({})
    const errors = ref({})
    const touched = ref({})
    
    const isValid = computed(() => {
        const result = SchemaValidator.validate(formData.value, schema)
        return result.isValid
    })
    
    const validateField = (fieldName) => {
        const fieldSchema = schema[fieldName]
        const fieldValue = formData.value[fieldName]
        
        const error = SchemaValidator.validateField(fieldName, fieldValue, fieldSchema)
        
        if (error) {
            errors.value[fieldName] = error
        } else {
            delete errors.value[fieldName]
        }
        
        touched.value[fieldName] = true
    }
    
    const validateForm = () => {
        const result = SchemaValidator.validate(formData.value, schema)
        errors.value = result.errors.reduce((acc, error) => {
            const [field] = error.split(' ')
            acc[field] = error
            return acc
        }, {})
        
        return result.isValid
    }
    
    const resetForm = () => {
        formData.value = {}
        errors.value = {}
        touched.value = {}
    }
    
    return {
        formData,
        errors,
        touched,
        isValid,
        validateField,
        validateForm,
        resetForm
    }
}

// In a component
// ProjectForm.vue
import { useFormValidation } from '@/composables/useFormValidation.js'
import { ProjectFormSchema } from '@/types/forms.js'

export default {
    setup() {
        const {
            formData,
            errors,
            touched,
            isValid,
            validateField,
            validateForm,
            resetForm
        } = useFormValidation(ProjectFormSchema)
        
        const handleSubmit = () => {
            if (validateForm()) {
                // Submit form
                console.log('Form is valid:', formData.value)
            } else {
                console.log('Form has errors:', errors.value)
            }
        }
        
        return {
            formData,
            errors,
            touched,
            isValid,
            validateField,
            handleSubmit,
            resetForm
        }
    }
}
```

---

## 7. Runtime Type Checking

```javascript
// utils/typeChecker.js
export class TypeChecker {
    static assertType(value, schema, context = '') {
        const result = SchemaValidator.validate({ value }, { value: schema })
        
        if (!result.isValid) {
            throw new TypeError(
                `Type assertion failed${context ? ` in ${context}` : ''}: ${result.errors.join(', ')}`
            )
        }
        
        return value
    }
    
    static isType(value, schema) {
        const result = SchemaValidator.validate({ value }, { value: schema })
        return result.isValid
    }
}

// Usage in services
import { TypeChecker } from '../utils/typeChecker.js'
import { UserSchema } from '../types/user.js'

export class UserService {
    async createUser(userData) {
        // Runtime type checking
        TypeChecker.assertType(userData, CreateUserRequestSchema, 'UserService.createUser')
        
        const response = await api.post('/users', userData)
        
        // Validate response
        TypeChecker.assertType(response.data, UserSchema, 'UserService.createUser response')
        
        return response.data
    }
}
```

---

## 8. File Organization for JS Types

```
types/
├── index.js              # Export all types
├── user.js               # User-related schemas
├── project.js            # Project-related schemas
├── file.js               # File-related schemas
├── api.js                # API response schemas
├── forms.js              # Form validation schemas
├── components.js         # Component prop schemas
└── enums.js              # Shared enumerations

features/
├── codemate/
│   └── types/
│       ├── index.js
│       ├── file.js       # Codemate-specific file types
│       └── project.js    # Codemate-specific project types
└── testing/
    └── types/
        ├── index.js
        └── workspace.js  # Testing workspace types
```

### Example index.js
```javascript
// types/index.js
export * from './user.js'
export * from './project.js'
export * from './file.js'
export * from './api.js'
export * from './forms.js'
export * from './components.js'
export * from './enums.js'

// Re-export validation utilities
export { SchemaValidator, TypeChecker } from '../utils/index.js'
```

This approach gives you strong type definitions and runtime validation while staying in pure JavaScript!