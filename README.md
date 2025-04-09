# 📦 Booking System API

A Laravel-based REST API for managing bookings and customers, with Dockerized setup and MySQL database support.

---

## 🚀 Getting Started

Follow these steps to get the project up and running:

### 1. Build and Start Docker Containers

Use the provided `Makefile` to easily build and run the Docker containers:

```bash
make up
```

This will build and spin up the Laravel application and MySQL database containers.

### 2. Set Up Environment File

```bash
cp .env.example .env
```

Make sure to update `.env` if needed (e.g., the DB host is usually `mysql` when using Docker Compose).

### 3. Run Migrations

To run database migrations, use the following command:

```bash
make migrate
```

This will reset and run the migrations to set up the database schema.

### 4. Seed Initial Data

To seed the database with sample user data:

```bash
make seed
```

This will run the `UserSeeder` to populate your database with the initial user data (email: admin@example.com, password: password)

---

## 🧪 Running Tests

To run PHPUnit tests inside the Docker container:

```bash
make test
```

This will execute your tests and display the results.

---

## 🔧 Other Commands

### 1. Destroy Docker Containers

To stop and remove all Docker containers and volumes:

```bash
make down
```

### 2. Execute Script Inside Docker App Service

To execute a custom script inside the Docker app container:

```bash
make exec-script CMD="php artisan cache:clear"
```

You can replace `"php artisan cache:clear"` with any custom script or artisan command.

---

## 📋 API Endpoints

| Method | Endpoint        | Description         |
|--------|------------------|---------------------|
| GET    | `/api/customers` | List all customers  |
| POST   | `/api/customers` | Create new customer |
| GET    | `/api/bookings`  | List bookings       |
| POST   | `/api/bookings`  | Create a booking    |

(Full API docs available at `/api/docs`)

---

## ✅ Features

- Laravel 10 + PHP 8.1+
- Dockerized setup with MySQL
- Repository pattern
- Sanctum authentication (optional)
- Form Request validation
- Enum support for booking status
- Unit tests with PHPUnit
- Seeders and factories for testing
