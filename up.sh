#!/bin/bash

echo "🚀 Starting Task Management API..."

# 1. Start containers in detached mode
docker-compose up -d --build

echo "📦 Installing composer dependencies..."
docker-compose exec app composer install

echo "🔑 Generating app key..."
docker-compose exec app php artisan key:generate --force

echo "🗄️ Running database migrations..."
docker-compose exec app php artisan migrate --force

echo "🗄️ Running database seeders..."
docker-compose exec app php artisan db:seed --force

echo "📂 Setting permissions..."
docker-compose exec app chmod -R 777 storage bootstrap/cache

echo "✅ Environment is up! Access it at: http://localhost:8000"
echo "🛠️  Running a quick health check with Pest..."
docker-compose exec app ./vendor/bin/pest --compact

echo "🔍 Running Static Analysis..."
docker-compose exec app ./vendor/bin/phpstan analyse --memory-limit=1G

echo "---"
echo "✅ API: http://localhost:8000"
echo "✅ MySQL (External): 127.0.0.1:33700"
echo "✅ Redis (External): 127.0.0.1:63890"
echo "✅ MailPit (External): 127.0.0.1:9025"
echo "---"