# Laravel Booking

Booking System REST API

## Requirements

- PHP >=8.2
- Docker
- Composer

## Features

- Customers and Bookings CRUD API
- CSV Export
- Docker support with Laravel Sail

## Installation

First of all, clone the repository and cd into the laravel-booking directory

```
git clone https://github.com/gspataro/laravel-booking-test.git
cd laravel-booking
```

Now, let's install dependencies

```
composer install
npm install
```

This project uses Docker and Laravel Sail. To run the docker container, run the following command:

```
./vendor/bin/sail up
```

Now you can run the migrations

```
./vendor/bin/sail migrate
```

If you want to run the seeder with the migrations, run this command instead

```
./vendor/bin/sail migrate --seed
```

## API Documentation

All the endpoints requires authentication first.

You can create your own user and login using the Authentication paragraph of this documentation. On login, the system will respond with a bearer token that you can use to access the other endpoints.

### Authentication

#### `POST` /api/register

**Request Body**

- name @string
- email @string
- password @string

#### `POST` /api/login

**Request Body**

- email @string
- password @string

#### `POST` /api/logout

### Customer

#### `GET` /api/customer/

**Query Params**

- per_page @int
- page @int

#### `GET` /api/customer/{id}

#### `POST` /api/customer

**Request Body**

- name @string
- surname @string
- email @string
- phone @string
- address @string

#### `PUT` /api/customer/{id}

**Request Body**

- name @string
- surname @string
- email @string
- phone @string
- address @string

#### `DELETE` /api/customer/{id}

#### `GET` /api/customer/{id}/bookings

**Query Params**

- per_page @int
- page @int

### Booking

#### `GET` /api/booking/

**Query Params**

- per_page @int
- page @int

#### `GET` /api/booking/{id}

#### `POST` /api/booking

**Request Body**

- customer_id @int
- title @string
- checkint @datetime
- checkout @datetime

#### `PUT` /api/booking/{id}

**Request Body**

- customer_id @int
- title @string
- checkint @datetime
- checkout @datetime

#### `DELETE` /api/booking/{id}

### Export

#### `GET` /api/export/customers

#### `GET` /api/export/bookings

## Testing

To run the test suite:

```
./vendor/bin/sail artisan test
```

To run static analysis with PHPStan and Larastan:

```
./vendor/bin/phpstan analyse
```

To run code linting with laravel pint:

```
./vendor/bin/pint
```
