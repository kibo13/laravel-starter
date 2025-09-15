#!/bin/sh
set -e

echo "🚀 Starting Laravel application..."

# Wait for database to be ready
echo "⏳ Waiting for database..."
while ! nc -z mysql 3306; do
  sleep 1
done
echo "✅ Database ready!"

# Install Composer dependencies
echo "📦 Installing dependencies..."
composer install --no-interaction

# Setup environment if not exists
if [ ! -f .env ]; then
    echo "⚙️ Setting up .env..."
    cp .env.example .env
    php artisan key:generate --no-interaction
fi

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force --no-interaction

# Set permissions
chmod -R 775 storage bootstrap/cache

echo "✅ Laravel ready!"

# Start the application
exec php-fpm
