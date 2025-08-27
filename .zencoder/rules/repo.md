---
description: Repository Information Overview
alwaysApply: true
---

# MCC Payroll System Information

## Summary
MCC Payroll is a Laravel-based web application for managing employee payroll, including different types of timesheets for various employee categories (fulltime, parttime, staff, and utility workers).

## Structure
- **app/**: Contains application logic with Models and Controllers
- **bootstrap/**: Laravel bootstrap files
- **config/**: Configuration files for the application
- **database/**: Database migrations, factories, and seeders
- **public/**: Publicly accessible files and entry point
- **resources/**: Frontend assets, views, and components
- **routes/**: Application routes definition
- **storage/**: Application storage for logs, cache, etc.
- **tests/**: Test files for the application

## Language & Runtime
**Language**: PHP
**Version**: ^8.2
**Framework**: Laravel ^12.0
**Build System**: Composer for PHP, Vite for frontend
**Package Manager**: Composer (PHP), npm (JavaScript)

## Dependencies
**Main Dependencies**:
- laravel/framework: ^12.0
- laravel/tinker: ^2.10.1

**Development Dependencies**:
- fakerphp/faker: ^1.23
- laravel/breeze: ^2.3
- laravel/pint: ^1.13
- laravel/sail: ^1.41
- pestphp/pest: ^3.8
- tailwindcss: ^4.0.0
- vite: ^7.0.4

## Build & Installation
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Build frontend assets
npm run build

# Set up environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate
```

## Database
**Default Connection**: SQLite
**Configuration**: Located in config/database.php
**Migrations**: Multiple timesheet tables for different employee types

## Main Components
**Models**:
- User: Authentication model
- Employee: Employee information
- FulltimeTimesheet: Fulltime employee timesheets
- ParttimeTimesheet: Part-time employee timesheets
- StaffTimesheet: Staff timesheets
- UtilityTimesheet: Utility worker timesheets

**Controllers**:
- AuthController: Handles authentication
- DashboardController: Main dashboard functionality
- EmployeeController: Employee management
- Various timesheet controllers for different employee types

## Frontend
**Framework**: TailwindCSS
**Build Tool**: Vite
**Main Files**:
- resources/css/app.css
- resources/js/app.js
- resources/views/: Blade templates

## Testing
**Framework**: Pest PHP (PHPUnit wrapper)
**Test Location**: tests/ directory
**Configuration**: phpunit.xml
**Run Command**:
```bash
php artisan test
# or
vendor/bin/pest
```