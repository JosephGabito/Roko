#!/bin/bash

# Plugin Release Script for WordPress.org
# Usage: ./bin/release.sh [version]

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PLUGIN_SLUG="roko"
PLUGIN_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BUILD_DIR="$PLUGIN_DIR/build"
TEMP_DIR="$BUILD_DIR/temp"

# Functions
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if version is provided
if [ -z "$1" ]; then
    log_error "Version number is required!"
    echo "Usage: $0 <version>"
    echo "Example: $0 1.2.3"
    exit 1
fi

VERSION="$1"
PACKAGE_NAME="${PLUGIN_SLUG}-${VERSION}.zip"

log_info "Starting release process for ${PLUGIN_SLUG} v${VERSION}"

# Create build directory
log_info "Creating build directory..."
rm -rf "$BUILD_DIR"
mkdir -p "$TEMP_DIR"

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    log_error "Composer is not installed or not in PATH"
    exit 1
fi

# Check if PHP is available
if ! command -v php &> /dev/null; then
    log_error "PHP is not installed or not in PATH"
    exit 1
fi

# Check PHP version compatibility
log_info "Checking PHP version compatibility..."
PHP_VERSION=$(php -r "echo PHP_VERSION;")
log_info "Current PHP version: $PHP_VERSION"

# Install dependencies with optimized autoloader
log_info "Installing production dependencies..."
cd "$PLUGIN_DIR"
composer install --no-dev --optimize-autoloader --no-interaction --quiet

# Run PHP 7.4 compatibility check if PHPCS is available
if command -v vendor/bin/phpcs &> /dev/null; then
    log_info "Running PHP 7.4 compatibility check..."
    if vendor/bin/phpcs --standard=PHPCompatibility --runtime-set testVersion 7.4- src/ roko.php --report=summary; then
        log_success "PHP 7.4 compatibility check passed"
    else
        log_warning "PHP 7.4 compatibility issues found - review above output"
        read -p "Continue with release? (y/N): " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            log_error "Release aborted"
            exit 1
        fi
    fi
else
    log_warning "PHPCS not available, skipping PHP compatibility check"
fi

# Run syntax check
log_info "Running PHP syntax check..."
find src/ -name "*.php" -exec php -l {} \; > /dev/null
php -l roko.php > /dev/null
log_success "PHP syntax check passed"

# Run WordPress Coding Standards if available
if command -v vendor/bin/phpcs &> /dev/null; then
    log_info "Running WordPress Coding Standards check..."
    if vendor/bin/phpcs --standard=WordPress src/ roko.php --report=summary; then
        log_success "WordPress Coding Standards check passed"
    else
        log_warning "WordPress Coding Standards issues found"
        read -p "Continue with release? (y/N): " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            log_error "Release aborted"
            exit 1
        fi
    fi
else
    log_warning "WordPress Coding Standards not available"
fi

# Copy plugin files to temp directory
log_info "Copying plugin files..."
rsync -av \
    --exclude='build/' \
    --exclude='bin/' \
    --exclude='composer.json' \
    --exclude='composer.lock' \
    --exclude='phpcs.xml' \
    --exclude='.git/' \
    --exclude='.gitignore' \
    --exclude='node_modules/' \
    --exclude='*.log' \
    --exclude='*.tmp' \
    --exclude='.DS_Store' \
    --exclude='Thumbs.db' \
    --exclude='*.md' \
    --exclude='tests/' \
    --exclude='*.xml' \
    --exclude='*.dist' \
    "$PLUGIN_DIR/" "$TEMP_DIR/$PLUGIN_SLUG/"

# Update version in main plugin file
log_info "Updating version in plugin file..."
sed -i.bak "s/Version: .*/Version: $VERSION/" "$TEMP_DIR/$PLUGIN_SLUG/roko.php"
sed -i.bak "s/define( 'ROKO_VERSION', .* );/define( 'ROKO_VERSION', '$VERSION' );/" "$TEMP_DIR/$PLUGIN_SLUG/roko.php"
rm "$TEMP_DIR/$PLUGIN_SLUG/roko.php.bak"

# Remove development dependencies from vendor if they exist
if [ -d "$TEMP_DIR/$PLUGIN_SLUG/vendor" ]; then
    log_info "Cleaning vendor directory..."
    find "$TEMP_DIR/$PLUGIN_SLUG/vendor" -name "*.md" -delete
    find "$TEMP_DIR/$PLUGIN_SLUG/vendor" -name "*.txt" -delete
    find "$TEMP_DIR/$PLUGIN_SLUG/vendor" -name "*.xml" -delete
    find "$TEMP_DIR/$PLUGIN_SLUG/vendor" -name "*.yml" -delete
    find "$TEMP_DIR/$PLUGIN_SLUG/vendor" -name "*.yaml" -delete
    find "$TEMP_DIR/$PLUGIN_SLUG/vendor" -name "composer.json" -delete
    find "$TEMP_DIR/$PLUGIN_SLUG/vendor" -name "composer.lock" -delete
    find "$TEMP_DIR/$PLUGIN_SLUG/vendor" -name ".git*" -delete
    find "$TEMP_DIR/$PLUGIN_SLUG/vendor" -type d -name "tests" -exec rm -rf {} + 2>/dev/null || true
    find "$TEMP_DIR/$PLUGIN_SLUG/vendor" -type d -name "test" -exec rm -rf {} + 2>/dev/null || true
    find "$TEMP_DIR/$PLUGIN_SLUG/vendor" -type d -name "docs" -exec rm -rf {} + 2>/dev/null || true
fi

# Create ZIP package
log_info "Creating ZIP package..."
cd "$TEMP_DIR"
if command -v zip &> /dev/null; then
    zip -r "$BUILD_DIR/$PACKAGE_NAME" "$PLUGIN_SLUG/" -q
else
    log_error "ZIP command not found. Please install zip utility."
    exit 1
fi

# Calculate file size
FILESIZE=$(du -h "$BUILD_DIR/$PACKAGE_NAME" | cut -f1)

# Cleanup temp directory
rm -rf "$TEMP_DIR"

# Restore development dependencies
log_info "Restoring development dependencies..."
cd "$PLUGIN_DIR"
composer install --quiet

# Final checks
log_info "Running final validation..."
if [ -f "$BUILD_DIR/$PACKAGE_NAME" ]; then
    log_success "Package created successfully: $PACKAGE_NAME ($FILESIZE)"
    
    # Test the ZIP file
    if zip -T "$BUILD_DIR/$PACKAGE_NAME" &> /dev/null; then
        log_success "ZIP file integrity check passed"
    else
        log_error "ZIP file integrity check failed"
        exit 1
    fi
    
    # Show package contents
    log_info "Package contents:"
    unzip -l "$BUILD_DIR/$PACKAGE_NAME" | head -20
    
    echo ""
    log_success "Release package ready: $BUILD_DIR/$PACKAGE_NAME"
    echo ""
    echo "Next steps:"
    echo "1. Test the plugin package in a clean WordPress installation"
    echo "2. Upload to WordPress.org SVN repository"
    echo "3. Create a GitHub release with this package"
    
else
    log_error "Failed to create package"
    exit 1
fi 