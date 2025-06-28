# Roko Security Plugin

WordPress security plugin - **Development in Progress**

## Development Setup

1. Clone and install dependencies:
```bash
git clone https://github.com/yourusername/roko.git
cd roko
composer install
```

## Development Workflow

### Composer Scripts

| Command | Description |
|---------|-------------|
| `composer test` | Run all tests (syntax, compatibility, coding standards) |
| `composer phpcs` | Check WordPress Coding Standards |
| `composer phpcbf` | Auto-fix coding standards issues |
| `composer php74-compat` | Check PHP 7.4 compatibility |
| `composer php70-compat` | Check PHP 7.0 compatibility |
| `composer syntax-check` | Validate PHP syntax |

### Quick Commands

```bash
# Run all quality checks
composer test

# Fix coding standards
composer phpcbf

# Check PHP 7.4 compatibility
composer php74-compat
```

## Code Standards

- WordPress Coding Standards (WPCS)
- PHP 7.0+ compatibility
- PSR-4 autoloading
- Snake_case for methods and classes

## Release

```bash
./bin/release.sh 1.0.0
```

---

**Note: This plugin is in active development and not ready for production use.** 