#!/bin/bash
set -e

echo "Starting deployment..."

# Make sure we're in the right directory (workspace)
# This will be handled by the github action ssh command doing `cd ${{ github.workspace }}`

# Install dependencies
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (force because it's production/staging)
php artisan migrate --force

echo "Deployment finished successfully!"
