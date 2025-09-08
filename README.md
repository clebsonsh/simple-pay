# Simple Pay

Simple Pay is a RESTful API developed in PHP with Laravel, designed to facilitate financial transfers between users. This project showcases a robust architecture, incorporating services for business logic, repositories for data access, and external service integrations for authorization and notifications.

## Features

- **Transfers**: Allows users to send money to other users (customers or merchants).
- **Validation**:
  - Validates that merchants cannot initiate transfers.
  - Ensures the payer has a sufficient balance for the transfer.
- **External Services**:
  - Authorizes transfers using an external service.
  - Notifies the payee after a successful transfer using an external service.
- **Queue System**: Handles notifications asynchronously using Laravel Horizon.
- **Dockerized Environment**: Fully containerized for easy setup and development.
- **Code Quality**: Utilizes Pint for code style and PHPStan for static analysis.

## Technologies

- **Backend**: PHP 8.3, Laravel 12
- **Database**: MariaDB
- **Cache/Queue**: Redis, Laravel Horizon
- **Containerization**: Docker, Docker Compose
- **Testing**: Pest
- **Code Quality**: Pint, PHPStan

## Getting Started

### Prerequisites

- Docker
- Docker Compose

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/clebsonsh/simple-pay.git
   cd simple-pay
   ```

2. **Set up the environment file:**
   ```bash
   cp .env.docker .env
   ```

3. **Build and run the application with Docker:**
   ```bash
   docker compose up -d --build
   ```

4. **Run database migrations and seeders:**
   ```bash
   docker compose exec app php artisan migrate:fresh --seed
   ```

The application will be accessible at `http://localhost:8080`.

## API Documentation

### Transfer Endpoint

- **URL**: `/api/v1/transfer`
- **Method**: `POST`
- **Description**: Creates a new financial transfer.

#### Request Body

| Field   | Type    | Description                        |
|---------|---------|------------------------------------|
| `value` | integer | The amount to transfer (in cents). |
| `payer` | string  | The UUID of the payer.             |
| `payee` | string  | The UUID of the payee.             |

#### Example Requests

##### Example of a Successful Request
*Note: The mock authorization service may occasionally deny the request. If it fails, please try again.*
```bash
curl -X POST http://localhost:8080/api/v1/transfer \
-H "Content-Type: application/json" \
-d '{
    "value": 1000,
    "payer": "327e38fc-300f-3889-a332-f8cd7371760a",
    "payee": "6d57f7e6-b0ff-3358-b4b6-65e914c220ae"
}'
```

##### Example of a Failed Request (Insufficient Balance)

```bash
curl -X POST http://localhost:8080/api/v1/transfer \
-H "Content-Type: application/json" \
-d '{
    "value": 1000,
    "payer": "83666071-2644-39c4-9815-5e339c32c995",
    "payee": "6d57f7e6-b0ff-3358-b4b6-65e914c220ae"
}'
```

##### Example of a Failed Request (Merchant as Payer)

```bash
curl -X POST http://localhost:8080/api/v1/transfer \
-H "Content-Type: application/json" \
-d '{
    "value": 1000,
    "payer": "6d57f7e6-b0ff-3358-b4b6-65e914c220ae",
    "payee": "83666071-2644-39c4-9815-5e339c32c995"
}'
```

#### Responses

- **201 Created**: The transfer was successful.
- **422 Unprocessable Entity**: Validation error (e.g., insufficient balance, merchant as payer).
- **403 Forbidden**: The transfer was denied by the authorization service.
- **500 Internal Server Error**: An unexpected error occurred.

## Running Tests

To run the test suite, execute the following command:

```bash
docker compose exec app vendor/bin/pest
```

To run tests with coverage:

```bash
docker compose exec app vendor/bin/pest --coverage
```
