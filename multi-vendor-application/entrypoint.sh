#!/bin/sh
set -e

echo "Waiting for PostgreSQL to be ready..."
while ! nc -z "$DB_HOST" "$DB_PORT"; do
  sleep 1
done
echo "PostgreSQL is ready!"

composer dump-autoload --optimize

# Run migrations and seed database
php artisan migrate --force
php artisan db:seed --force

# Storage setup
php artisan storage:link

# Start PHP-FPM
exec "$@"