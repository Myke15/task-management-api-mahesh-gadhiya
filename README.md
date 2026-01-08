# Task Management API

## Overview

A robust task management API built with Laravel 12 that allows users to manage projects and tasks efficiently. The API provides comprehensive user authentication, project management, and task tracking capabilities with a clean RESTful interface.

### Key Features
- 🔐 User authentication and registration (Laravel Sanctum)
- 📋 Project management (CRUD operations)
- ✅ Task management (CRUD operations)
- 📧 Welcome email notifications for new users
- 🔍 Advanced filtering and pagination for task list
- 📊 Static code analysis with PHPStan Level 6
- 🧪 Comprehensive testing with Pest PHP

## Tech Stack

- **PHP**: 8.3
- **Framework**: Laravel 12
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **Testing**: Pest PHP
- **Static Analysis**: PHPStan Level 6 (Larastan)

## Prerequisites

- PHP >= 8.3
- Composer
- MySQL 8.0+

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Myke15/task-management-api-mahesh-gadhiya.git
   cd task-management-api-mahesh-gadhiya
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   - Update your `.env` file with database credentials
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

## Quick Setup (Alternative)

Use the built-in setup script:
```bash
composer run setup
```

## Running the Application

### Development Server
```bash
php artisan serve
```

### Using Docker
```bash
./up.sh  # Start containers
./down.sh  # Stop containers
```

## Running Tests

```bash
# Run all tests
php artisan test

# For Docker environment
docker-compose exec app ./vendor/bin/pest

# Run tests with coverage
php artisan test --coverage

# For Docker environment
docker-compose exec app ./vendor/bin/pest --coverage

## Static Analysis

```bash
# Run PHPStan analysis
./vendor/bin/phpstan analyse

# For Docker environment
docker-compose exec app ./vendor/bin/phpstan analyse --memory-limit=1G

```

## API Documentation

### Authentication Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST   | `/api/register` | Register new user | No |
| POST   | `/api/login` | Login user | No |
| POST   | `/api/logout` | Logout user | Yes |
| GET    | `/api/user` | Get current user profile | Yes |

### Project Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET    | `/api/projects` | List user's projects | Yes |
| POST   | `/api/projects` | Create new project | Yes |
| GET    | `/api/projects/{project}` | Get project details | Yes |
| PUT    | `/api/projects/{project}` | Update project | Yes |
| DELETE | `/api/projects/{project}` | Delete project | Yes |

### Task Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET    | `/api/projects/{project}/tasks` | List project tasks | Yes |
| POST   | `/api/projects/{project}/tasks` | Create task in project | Yes |
| GET    | `/api/tasks/{task}` | Get task details | Yes |
| PUT    | `/api/tasks/{task}` | Update task | Yes |
| DELETE | `/api/tasks/{task}` | Delete task | Yes |

### Example API Usage

#### Authentication
```bash
# Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password","password_confirmation":"password"}'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password"}'
```

#### Project Management
```bash
# Create project
curl -X POST http://localhost:8000/api/projects \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"My Project","description":"Project description","status":"pending"}'

# List projects
curl -X GET http://localhost:8000/api/projects \
  -H "Authorization: Bearer YOUR_TOKEN"
```

#### Task Management
```bash
# Create task
curl -X POST http://localhost:8000/api/projects/1/tasks \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title":"Task title","description":"Task description","status":"todo","priority":"high","due_date":"2024-12-31"}'
```

For more API information, kindly refere task-management-api.postman_collection.json

## Architecture Decisions

### Repository Pattern
- **Decision**: Implemented Repository pattern for data access
- **Rationale**: Provides abstraction layer between business logic and data persistence, making the code more testable and maintainable

### Service Layer
- **Decision**: Added Service layer for business logic
- **Rationale**: Separates complex business operations from controllers, promoting single responsibility principle

### Contract-First Approach
- **Decision**: Used interfaces (contracts) for all major components
- **Rationale**: Enables dependency inversion, making the application more flexible and testable

### Event-Driven Architecture
- **Decision**: Implemented events for user registration
- **Rationale**: Enables loose coupling and easy extension of functionality (e.g., welcome emails)

### Enum Usage
- **Decision**: Used PHP 8.1+ enums for status and priority values
- **Rationale**: Provides type safety and prevents invalid values