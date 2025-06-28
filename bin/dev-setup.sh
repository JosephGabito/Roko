#!/bin/bash
# Development setup script
# Sets up the development environment for Roko WordPress plugin

set -e

echo "Setting up development environment..."

# Install PHP dependencies via Composer
echo "Installing Composer dependencies..."
composer install

# Install Git hooks (if you want pre-commit checks)
if [ -d ".git" ]; then
    echo "Setting up Git hooks..."
    # Create pre-commit hook that runs quality checks before commits
    cat > .git/hooks/pre-commit << 'EOF'
#!/bin/bash
# Run tests before commit
composer test
EOF
    chmod +x .git/hooks/pre-commit
    echo "✅ Git pre-commit hook installed"
fi

echo "✅ Development environment ready!"
echo ""
echo "Next steps:"
echo "  - Run 'composer test' to check code quality"
echo "  - Run 'composer phpunit' to run unit tests"
echo "  - Run 'composer phpcbf' to auto-fix coding standards" 