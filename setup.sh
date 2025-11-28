#!/bin/bash

# =================================================
# Moslimani Platform Setup Script
# =================================================

set -e

echo "🚀 Setting up Moslimani Platform..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file from env.example.txt..."
    cp env.example.txt .env
fi

# Install dependencies
echo "📦 Installing Composer dependencies..."
composer install

# Generate app key
echo "🔑 Generating application key..."
php artisan key:generate

# Generate JWT secret
echo "🔐 Generating JWT secret..."
php artisan jwt:secret --force

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate

# Seed the database
echo "🌱 Seeding database..."
php artisan db:seed

# Cache configurations (optional for production)
# echo "⚡ Caching configurations..."
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

echo ""
echo "✅ Setup complete!"
echo ""
echo "📋 Next steps:"
echo "   1. Update .env with your database credentials"
echo "   2. Run: php artisan serve"
echo "   3. Access admin panel at: http://localhost:8000/admin"
echo "   4. Login with: admin@moslimani.com / password"
echo ""
echo "🔧 Development credentials:"
echo "   Admin Email: admin@moslimani.com"
echo "   Admin Password: password"
echo ""



