#!/bin/bash
# Development setup script

set -e

echo "Setting up development environment..."

# Install dependencies
composer install

# Install Git hooks (if you want pre-commit checks)
if [ -d ".git" ]; then
    echo "Setting up Git hooks..."
    cat > .git/hooks/pre-commit << 'EOF'
#!/bin/bash
# Run tests before commit
composer test
EOF
    chmod +x .git/hooks/pre-commit
fi

echo "Development environment ready!" 