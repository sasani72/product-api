# Product API Service

A REST API service for managing products built with PHP 8.2 and PostgreSQL.

## Requirements

- PHP 8.2 or higher
- PostgreSQL 15 or higher
- Composer
- Docker and Docker Compose

## Installation

### Using Docker

1. Clone the repository
2. Run `docker-compose up -d`
3. The API will be available at `http://localhost:8080`
4. Database migrations will be automatically applied

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

## Testing

### Running Tests
```bash
# Using Docker
docker-compose exec app ./vendor/bin/phpunit

```

The test suite includes:
- Unit tests for Product model, repository, and validation
- Integration tests for API endpoints
- Test database is automatically configured

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

## License

This project is licensed under the MIT License.