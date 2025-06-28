#!/bin/bash
# Optimize plugin for production

echo "Optimizing plugin..."

# Optimize Composer autoloader
composer dump-autoload --optimize --no-dev

# Minify CSS/JS if you have build tools
# npm run build

echo "Optimization completed" 