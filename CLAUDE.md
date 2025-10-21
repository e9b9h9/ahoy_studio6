# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## User Preferences DO NOT DELETE

- Always review your plan before you make changes to code
- Wait for me to approve the plan before making changes to code files
- Ask questions to clarify
- Do not jump ahead or add extra things unless you ask
- Use javascript over typescript where possible
- Always review code changes by file name when complete

## Plan Presentation Format

When presenting plans, use the following structure:
- **Clear section headers** with purpose
- **Files to Create** - List new files with brief description
- **Files to Modify** - List existing files with bullet points of specific changes
- **Implementation Details** - Step-by-step logic flow
- **Questions** - Numbered list of clarifications needed
- Always wait for approval before proceeding

## Styling Preferences

- Simple/Minimal and condensed UI

## Project Overview

This is a Laravel + Vue 3 + Inertia.js application with TypeScript, using Tailwind CSS v4 for styling. The project uses Laravel Wayfinder for type-safe routing between backend and frontend, and Laravel Fortify for authentication with two-factor authentication support.

## Commands DO NOT DELETE
you cannot run php commands, tell me to do it and I will tell you the result 

## Development Commands

### Run Development Server
```bash
composer run dev
```
This runs Laravel server, queue listener, and Vite dev server concurrently.

### Build for Production
```bash
npm run build
```

### Run Tests
```bash
# Run all tests
composer test

# Run a specific test file
php artisan test tests/Feature/Auth/AuthenticationTest.php

# Run a specific test method
php artisan test --filter=test_users_can_authenticate_using_the_login_screen
```

### Code Quality
```bash
# Format PHP code
vendor/bin/pint

# Lint and fix JavaScript/TypeScript
npm run lint

# Format JavaScript/TypeScript/Vue files
npm run format

# Check formatting without fixing
npm run format:check
```

### Database Commands
```bash
# Run migrations
php artisan migrate

# Refresh database with seeds
php artisan migrate:fresh --seed

# Create a new migration
php artisan make:migration create_example_table
```

### Laravel Commands
```bash
# Clear all caches
php artisan optimize:clear

# Generate Wayfinder TypeScript routes
php artisan wayfinder:generate

# Create a new controller
php artisan make:controller ExampleController

# Create a new model with migration
php artisan make:model Example -m
```

## Architecture

### Frontend Structure
- **Pages**: Located in `resources/js/pages/` - Inertia.js page components
- **Components**: `resources/js/components/` - Reusable Vue components
- **UI Components**: `resources/js/components/ui/` - Shadcn-vue inspired components built with Reka UI
- **Layouts**: `resources/js/layouts/` - Application layout components
- **Actions**: `resources/js/actions/` - TypeScript-generated route definitions from Wayfinder
- **Composables**: `resources/js/composables/` - Vue composition API utilities
- **Features**: `resources/js/features/` - Feature-specific components and stores

### Backend Structure
- **Controllers**: `app/Http/Controllers/` - HTTP request handlers
- **Models**: `app/Models/` - Eloquent ORM models
- **Routes**: `routes/` - Application routing (web.php, auth.php, settings.php, codemate.php, codemate-settings.php)
- **Migrations**: `database/migrations/` - Database schema definitions
- **Requests**: `app/Http/Requests/` - Form request validation classes
- **Middleware**: `app/Http/Middleware/` - Custom middleware

### Key Technologies
- **Laravel Wayfinder**: Generates TypeScript route definitions from Laravel routes for type-safe navigation
- **Inertia.js**: Enables SPA-like experience while using server-side routing
- **Laravel Fortify**: Provides authentication features including two-factor authentication
- **PrimeVue**: Component library for data tables and complex UI components
- **Reka UI**: Headless UI components for building accessible interfaces

### Database
- Default configuration uses SQLite (`database/database.sqlite`)
- Session and cache storage use database drivers

### Authentication Flow
- Login/Register pages use Inertia forms with server-side validation
- Two-factor authentication is implemented via Laravel Fortify
- Protected routes use `auth` and `verified` middleware

### State Management
- Server state is managed through Inertia props
- Client state uses Vue 3's reactivity system
- Theme/appearance state uses localStorage with VueUse composables

## Application Features

### Codemate Feature
This application includes a "Codemate" feature which appears to be a file management/monitoring system:
- **Controllers**: Located in `app/Http/Codemate/` and `app/Http/CodemateSettings/`
- **Frontend**: Components in `resources/js/features/codemate/` and `resources/js/features/codemate-settings/`
- **Models**: `FolderFile` and `Feature` models for file system representation
- **Routes**: Separate route files for codemate functionality (`routes/codemate.php`, `routes/codemate-settings.php`)
- **File Processing**: `InitializeFileService` handles file initialization and indexing

### Testing Framework
- Uses **Pest PHP** testing framework (not PHPUnit)
- Test structure follows Laravel conventions in `tests/Feature/` and `tests/Unit/`
- Authentication tests include two-factor authentication testing

### SSR Support
- Server-side rendering configured with Inertia.js
- SSR entry point: `resources/js/ssr.ts`
- Development with SSR: `composer run dev:ssr`

### Additional Development Notes
- Project uses Pinia for state management (see `resources/js/features/codemate/codemateStore.js`)
- TypeScript configuration includes custom route definitions via Wayfinder
- Tailwind CSS v4 with custom configuration and theme variables
- Component library combines Reka UI, PrimeVue, and custom components
- Font: Instrument Sans
- Dark mode support via CSS variables and VueUse composables

## Important Files and Conventions

### Configuration Files
- `vite.config.ts` - Vite configuration with Laravel plugin
- `tsconfig.json` - Strict TypeScript configuration
- `eslint.config.js` - ESLint with Vue 3 and TypeScript
- `components.json` - Shadcn-vue component configuration
- `config/fortify.php` - Authentication configuration
- `config/inertia.php` - SSR configuration

### Type-Safe Routing
When creating new routes:
1. Define route in appropriate file under `routes/`
2. Run `php artisan wayfinder:generate` to update TypeScript definitions
3. Use generated actions from `resources/js/actions/` in frontend code

### Component Creation
When creating new Vue components:
1. Check existing components for patterns and conventions
2. Use Composition API with TypeScript
3. Follow existing naming conventions
4. For UI components, prefer Reka UI or existing patterns in `components/ui/`