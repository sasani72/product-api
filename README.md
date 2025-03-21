# Product API Service

A REST API service for managing products built with PHP 8.2 and PostgreSQL.

## Requirements

- PHP 8.2 or higher
- PostgreSQL 15 or higher
- Composer
- Docker and Docker Compose (optional)

## Installation

### Using Docker (Recommended)

1. Clone the repository
2. Run `docker-compose up -d`
3. The API will be available at `http://localhost:8080`
4. Database migrations will be automatically applied

### Manual Installation

1. Clone the repository
2. Run `composer install`
3. Create a PostgreSQL database
4. Copy `.env.example` to `.env` and configure your database connection:
   ```
   DB_HOST=localhost
   DB_PORT=5432
   DB_NAME=productdb
   DB_USER=your_user
   DB_PASSWORD=your_password
   ```
5. Run database migrations:
   ```bash
   psql -U your_user -d productdb -f migrations/create_products_table.sql
   ```
6. Run `php -S localhost:8080 -t public`

## Database Structure

The service uses PostgreSQL with the following schema:

```sql
CREATE TABLE products (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    attributes JSONB NOT NULL DEFAULT '{}',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);
```

## API Endpoints

- `POST /products` - Create a new product
  ```json
  {
    "name": "Product Name",
    "price": 100.50,
    "category": "electronics",
    "attributes": {
      "brand": "Brand Name",
      "color": "black"
    }
  }
  ```
- `GET /products/{id}` - Get product details
- `PATCH /products/{id}` - Update a product (partial update)
- `DELETE /products/{id}` - Delete a product
- `GET /products` - List products (with optional filtering)
  - Query parameters:
    - `category`: Filter by category
    - `price_min`: Minimum price
    - `price_max`: Maximum price

## Architecture

The project follows a clean architecture pattern with the following layers:

1. **Controllers**: Handle HTTP requests and responses
2. **Services**: Implement business logic
3. **Repositories**: Handle data persistence
4. **DTOs**: Data Transfer Objects for request/response handling
5. **Validators**: Input validation and data sanitization
6. **Models**: Domain models representing business entities

## Testing

### Setup
The test environment is configured in `tests/bootstrap.php` which:
- Sets up test environment variables
- Configures database connection
- Loads application configuration
- Suppresses output during test execution

### Running Tests
```bash
composer test
```

The test suite includes:
- Unit tests for Product model, repository, and validation
- Integration tests for API endpoints
- Test database is automatically configured

### Test Structure
```
tests/
├── bootstrap.php    # Test environment setup
├── Integration/     # Integration tests
└── Unit/           # Unit tests
    ├── Product/    # Product-related unit tests
    └── Validation/ # Validation-related unit tests
```

## Development

- Run PHPStan for static analysis:
```bash
composer phpstan
```

- Run CodeSniffer:
```bash
composer cs-check
composer cs-fix
```

## Project Structure

```
├── config/           # Configuration files
├── controllers/      # API Controllers
├── DTO/             # Data Transfer Objects
├── exceptions/      # Custom exceptions
├── migrations/      # Database migrations
├── models/          # Domain models
├── public/          # Public entry point
├── repositories/    # Database repositories
├── services/        # Business logic
├── validators/      # Input validation
└── tests/           # Test files
    ├── bootstrap.php # Test environment setup
    ├── Integration/ # Integration tests
    └── Unit/        # Unit tests
```

## Error Handling

The API returns appropriate HTTP status codes:
- 200: Success
- 201: Created
- 400: Bad Request (validation errors)
- 404: Not Found
- 405: Method Not Allowed
- 500: Internal Server Error

## Contributing

1. Fork the repository
2. Create your feature branch
3. Run tests and ensure they pass
4. Submit a pull request

## License

This project is licensed under the MIT License.