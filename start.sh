#!/usr/bin/env bash

# Exit on error
set -e

echo "==> Running Laravel Railway Deployment Setup..."

# Create required framework directories
mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache bootstrap/cache

# Fix permissions
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Run migrations and seed
echo "==> Running Database Migrations & Seeding..."
php artisan migrate --seed --force

# Create storage symlink
echo "==> Creating Storage Link..."
php artisan storage:link --force || true

# Cache configurations, routes, and views
echo "==> Optimizing & Caching Application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP server
PORT="${PORT:-8000}"
echo "==> Starting application on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port="$PORT"
