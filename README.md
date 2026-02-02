# DocuPet - Pet Registration Application

A Laravel-based pet registration application that demonstrates multiple frontend approaches (Vue.js SPA and Blade templates) with a clean architecture using the Repository Pattern and Service Layer.

## Table of Contents

- [Project Overview](#project-overview)
- [Frontend Approaches](#frontend-approaches)
  - [Vue.js SPA Path](#vuejs-spa-path)
  - [Blade Templates Path](#blade-templates-path)
  - [Comparison](#comparison)
- [API Endpoints](#api-endpoints)
  - [API Protection](#api-protection)
- [Installation](#installation)
  - [Docker (Recommended)](#docker-recommended)
  - [Local Development](#local-development)
- [Project Structure](#project-structure)
- [Architecture & Patterns](#architecture--patterns)

---

## Project Overview

DocuPet allows users to register their pets with the following information:
- Pet type (Dog, Cat, etc.)
- Pet name and gender
- Breed selection (with support for unknown/mixed breeds)
- Date of birth or approximate age

The application showcases two different frontend implementations sharing the same backend API and business logic.

---

## Frontend Approaches

### Vue.js SPA Path

**URL:** `/vue-app`

A single-page application built with Vue.js that:
- Fetches data dynamically via API calls
- Provides a reactive, modern user experience
- Uses searchable dropdowns for type and breed selection
- Submits pet registration via the API

**Key Files:**
- `resources/js/app.js` - Vue app entry point
- `resources/js/layouts/RegistrationForm.vue` - Main registration form
- `resources/js/components/` - Reusable Vue components
- `resources/views/vue/vue-app-container.blade.php` - Blade wrapper for Vue app

### Blade Templates Path

**URL:** `/pet-owner/register`

A traditional server-rendered form using Laravel Blade templates that:
- Loads all data server-side (types, breeds)
- Uses standard HTML form submission
- Validates via Laravel Form Requests
- Provides a simpler, JavaScript-minimal approach

**Key Files:**
- `resources/views/blades/pet-owner-form.blade.php` - Registration form template
- `app/Http/Controllers/PetOwnerRegistrationController.php` - Form controller
- `app/Http/Requests/PetSaveRequest.php` - Form validation rules

### Comparison

| Feature | Vue.js SPA | Blade Templates |
|---------|-----------|-----------------|
| Data Loading | API calls (async) | Server-side (sync) |
| Form Submission | API POST | Traditional form POST |
| User Experience | Dynamic, reactive | Page reloads |
| JavaScript Required | Yes | Minimal |
| SEO | Requires SSR | Native |
| Complexity | Higher | Lower |

---

## API Endpoints

All API routes are prefixed with `/api`.

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/types` | List all pet types |
| GET | `/api/breeds` | List all breeds (supports `?type_id=` filter) |
| POST | `/api/pets` | Register a new pet |

### API Protection

API endpoints are protected by an API key middleware (`EnsureApiKey`).

**Authentication Methods:**

1. **X-API-Key Header:**
   ```bash
   curl -H "X-API-Key: your-api-key" http://localhost:8000/api/types
   ```

2. **Bearer Token:**
   ```bash
   curl -H "Authorization: Bearer your-api-key" http://localhost:8000/api/types
   ```

**Configuration:**

The API key is configured via the `API_KEY` environment variable. In Docker, this is set in `docker-compose.yml`:

```yaml
environment:
  API_KEY: docupet-api-key-secret
```

For local development, add to your `.env` file:

```env
API_KEY=your-secret-api-key
```

**Error Responses:**

- `401 Unauthorized` - Invalid or missing API key
- `503 Service Unavailable` - API key not configured on server

---

## Installation

### Docker (Recommended)

Docker provides the easiest setup and works on **macOS**, **Linux**, and **Windows**.

**Prerequisites:**
- [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/) (Docker Desktop includes both)

**Quick Start:**

```bash
# Clone the repository (HTTPS)
git clone https://github.com/evan7diab/docupet-code-challenge.git

# Or clone via SSH
git clone git@github.com:evan7diab/docupet-code-challenge.git

cd docupet-code-challenge

# Build and start the application
docker compose up -d --build

# The app will be available at:
# - Home: http://localhost:8000
# - Vue App: http://localhost:8000/vue-app
# - Blade Form: http://localhost:8000/pet-owner/register
```

**Useful Commands:**

```bash
# View application logs
docker compose logs -f app

# Stop the application
docker compose down

# Stop and remove database volume (reset data)
docker compose down -v

# Rebuild after code changes
docker compose up -d --build
```

**Local Development with Docker:**

To mount your code and see changes without rebuilding:

1. Add volume mount in `docker-compose.yml` under `app` service:
   ```yaml
   volumes:
     - .:/var/www/html
   ```

2. Run `composer install` and `npm run build` on your host

3. Restart: `docker compose up -d`

### Local Development

**Prerequisites:**
- PHP 8.2+
- Composer
- Node.js 20+
- MySQL 8.0

**Setup:**

```bash
# Install PHP dependencies
composer install

# Install Node dependencies and build assets
npm install
npm run build

# Copy environment file
cp .env.example .env

# Configure your database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=docupet
# DB_USERNAME=root
# DB_PASSWORD=

# Generate application key
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Start development server
php artisan serve
```

---

## Project Structure

```
Docupet/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/                    # API controllers
│   │   │   │   ├── BreedController.php
│   │   │   │   ├── PetController.php
│   │   │   │   └── TypeController.php
│   │   │   ├── PetOwnerRegistrationController.php  # Blade form controller
│   │   │   └── VueAppController.php    # Vue app container
│   │   ├── Middleware/
│   │   │   └── EnsureApiKey.php        # API key authentication
│   │   └── Requests/
│   │       └── PetSaveRequest.php      # Form validation
│   ├── Models/                         # Eloquent models
│   │   ├── Breed.php
│   │   ├── Pet.php
│   │   └── Type.php
│   ├── Providers/
│   │   └── AppServiceProvider.php      # Repository bindings
│   ├── Repositories/                   # Repository pattern
│   │   ├── BreedRepository.php
│   │   ├── BreedRepositoryInterface.php
│   │   ├── PetRepository.php
│   │   ├── PetRepositoryInterface.php
│   │   ├── TypeRepository.php
│   │   └── TypeRepositoryInterface.php
│   └── Services/                       # Business logic
│       ├── BreedService.php
│       ├── PetOwnerRegistrationService.php
│       └── TypeService.php
├── database/
│   ├── migrations/                     # Database schema
│   └── seeders/                        # Sample data
├── resources/
│   ├── js/                             # Vue.js frontend
│   │   ├── components/                 # Vue components
│   │   ├── layouts/                    # Page layouts
│   │   └── app.js                      # Vue entry point
│   └── views/
│       ├── blades/                     # Blade templates
│       └── vue/                        # Vue container
├── routes/
│   ├── api.php                         # API routes
│   └── web.php                         # Web routes
├── docker-compose.yml                  # Docker services
├── Dockerfile                          # App container
└── docker-entrypoint.sh                # Container startup
```

---

## Architecture & Patterns

### Repository Pattern

The application uses the Repository Pattern to abstract data access from business logic.

**Structure:**
- **Interface** (`RepositoryInterface`) - Defines the contract
- **Implementation** (`Repository`) - Implements data access using Eloquent
- **Binding** - Interfaces are bound to implementations in `AppServiceProvider`

**Example:**

```php
// Interface
interface PetRepositoryInterface {
    public function create(array $data): Pet;
}

// Implementation
class PetRepository implements PetRepositoryInterface {
    public function create(array $data): Pet {
        return Pet::create($data);
    }
}

// Binding in AppServiceProvider
$this->app->bind(PetRepositoryInterface::class, PetRepository::class);
```

**Benefits:**
- Decouples business logic from data access
- Makes testing easier (mock repositories)
- Allows swapping data sources without changing services

### Service Layer

Business logic is encapsulated in Service classes, keeping controllers thin.

**Example:**

```php
class PetOwnerRegistrationService {
    public function __construct(
        private PetRepositoryInterface $petRepository
    ) {}

    public function savePet(array $data): Pet {
        // Business logic: validate, transform, compute values
        // ...
        return $this->petRepository->create($petData);
    }
}
```

**Benefits:**
- Controllers handle HTTP concerns only
- Business logic is reusable and testable
- Clear separation of concerns

### Form Request Validation

Laravel Form Requests encapsulate validation rules.

```php
class PetSaveRequest extends FormRequest {
    public function rules(): array {
        return [
            'type_id' => 'required|exists:types,id',
            'name' => 'required|string|max:255',
            // ...
        ];
    }
}
```

### Dependency Injection

The application leverages Laravel's IoC container for dependency injection:

- Controllers receive Services via constructor injection
- Services receive Repositories via constructor injection
- Bindings are configured in `AppServiceProvider`

This enables loose coupling and facilitates unit testing.

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
