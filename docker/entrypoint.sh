#!/bin/bash

# Wait for PostgreSQL to be ready
echo "Waiting for PostgreSQL to be ready..."
while ! pg_isready -h db -p 5432 -U user
do
  sleep 2
done
echo "PostgreSQL is ready!"

# Run migrations
echo "Running database migrations..."
PGPASSWORD=password psql -h db -U user -d productdb -f /var/www/html/migrations/create_products_table.sql

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec php-fpm 