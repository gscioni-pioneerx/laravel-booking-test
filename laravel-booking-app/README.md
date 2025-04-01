# Laravel Booking System API

This is a RESTful API for a booking system built with Laravel 11. It allows you to manage customers and their bookings.

## Features

-   CRUD operations for Customers and Bookings
-   Validation with Form Requests
-   Repository Pattern implementation
-   Service Layer architecture
-   Strategy Pattern for booking status handling
-   CSV export functionality
-   API documentation with Swagger/OpenAPI
-   Comprehensive test suite
-   Docker containerization

## Requirements

-   PHP 8.3+
-   Composer
-   Docker & Docker Compose

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/yourusername/laravel-booking-system.git
    cd laravel-booking-system
    ```

2. Start the Docker containers:

    ```bash
    docker-compose up -d
    ```

3. Install dependencies:

    ```bash
    docker-compose exec app composer install
    ```

4. Copy the environment file:

    ```bash
    cp .env.example .env
    ```

5. Generate application key:

    ```bash
    docker-compose exec app php artisan key:generate
    ```

6. Run migrations and seeders:
    ```bash
    docker-compose exec app php artisan migrate --seed
    ```

## API Documentation

The API documentation is available at `/api/documentation` when the application is running.

You can also import the Postman collection from the `Booking API.postman_collection.json` file.

## API Endpoints

### Customers

-   `GET /api/v1/customers` - Get all customers
-   `POST /api/v1/customers` - Create a new customer
-   `GET /api/v1/customers/{id}` - Get a customer by ID
-   `PUT /api/v1/customers/{id}` - Update a customer
-   `DELETE /api/v1/customers/{id}` - Delete a customer

### Bookings

-   `GET /api/v1/bookings` - Get all bookings
-   `POST /api/v1/bookings` - Create a new booking
-   `GET /api/v1/bookings/{id}` - Get a booking by ID
-   `PUT /api/v1/bookings/{id}` - Update a booking
-   `DELETE /api/v1/bookings/{id}` - Delete a booking
-   `GET /api/v1/bookings/export/csv` - Export bookings to CSV
-   `GET /api/v1/customers/{customerId}/bookings` - Get bookings by customer ID

### Customer ID

-   `GET /api/v1/bookings/active` - Get currently active bookings
-   `GET /api/v1/bookings/future` - Get future bookings
-   `GET /api/v1/bookings/status/{status}` - Get bookings by status

## Testing

Run the test suite:

```bash
docker-compose exec app php artisan test
```

Run static analysis:

```bash
docker-compose exec app ./vendor/bin/phpstan analyse
```

Run code linting:

```bash
docker-compose exec app ./vendor/bin/pint
```

## Architecture

This project follows a layered architecture:

1. **Controllers**: Handle HTTP requests and responses
2. **Services**: Contain business logic
3. **Repositories**: Handle data access
4. **Models**: Represent database entities

Design patterns used:

-   Repository Pattern
-   Service Layer
-   Strategy Pattern
-   Factory Pattern

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

````

### 27. Create a .env.example file

```env:.env.example
APP_NAME="Laravel Booking System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=user
DB_PASSWORD=secret

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# API token for simple authentication
API_TOKEN=your_secure_api_token_here
````

### 28. Update the AppServiceProvider to Register the API Token

```php:app/Providers/AppServiceProvider.php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register API token in config
        config(['app.api_token' => env('API_TOKEN', 'default_secure_token')]);
    }

    public function boot(): void
    {
        //
    }
}
```

### 29. Create a Command to Generate API Token

```bash
docker-compose exec app php artisan make:command GenerateApiToken
```

Define the GenerateApiToken command:

```php:app/Console/Commands/GenerateApiToken.php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateApiToken extends Command
{
    protected $signature = 'api:token:generate';
    protected $description = 'Generate a secure API token and update .env file';

    public function handle(): int
    {
        $token = Str::random(64);

        $envPath = base_path('.env');

        if (File::exists($envPath)) {
            $envContent = File::get($envPath);

            if (preg_match('/^API_TOKEN=.*$/m', $envContent)) {
                $envContent = preg_replace('/^API_TOKEN=.*$/m', 'API_TOKEN=' . $token, $envContent);
            } else {
                $envContent .= "\nAPI_TOKEN=" . $token;
            }

            File::put($envPath, $envContent);

            $this->info('API token generated successfully: ' . $token);
            return Command::SUCCESS;
        }

        $this->error('.env file not found.');
        return Command::FAILURE;
    }
}
```

### 30. Final Steps

1. Run migrations and seeders:

```bash
docker-compose exec app php artisan migrate --seed
```

2. Generate API documentation:

```bash
docker-compose exec app php artisan l5-swagger:generate
```

3. Generate API token:

```bash
docker-compose exec app php artisan api:token:generate
```

4. Run tests:

```bash
docker-compose exec app php artisan test
```

5. Run static analysis:

```bash
docker-compose exec app ./vendor/bin/phpstan analyse
```

6. Run code linting:

```bash
docker-compose exec app ./vendor/bin/pint
```
