#!/bin/bash
# Optimize codebase for production deployment
# Removes development files and runs performance optimizations

echo "🚀 Optimizing codebase for production..."

# Remove development and testing files that shouldn't be in production
echo "• Removing development files..."
rm -rf tests/
rm -rf .git/
rm -rf node_modules/
rm -f composer.lock
rm -f .gitignore

# Run Composer with production optimizations
# --no-dev: Skip development dependencies
# --optimize-autoloader: Generate optimized autoloader for better performance
echo "• Running Composer install with production optimizations..."
composer install --no-dev --optimize-autoloader

echo "✅ Codebase optimized for production deployment" 