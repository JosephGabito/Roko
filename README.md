# Super Secret WordPress Plugin

<!-- BADGES-START -->
[![Build Status](https://img.shields.io/github/actions/workflow/status/JosephGabito/roko/code-quality.yml?branch=main&label=build)](https://github.com/JosephGabito/roko/actions/workflows/code-quality.yml)
[![Unit Tests](https://img.shields.io/github/actions/workflow/status/JosephGabito/roko/code-quality.yml?branch=main&label=tests&job=Unit%20Tests)](https://github.com/JosephGabito/roko/actions/workflows/code-quality.yml)
[![WordPress Linting](https://img.shields.io/github/actions/workflow/status/JosephGabito/roko/code-quality.yml?branch=main&label=WordPress&job=WordPress%20Linting)](https://github.com/JosephGabito/roko/actions/workflows/code-quality.yml)
[![PHP 7.4 Compatibility](https://img.shields.io/github/actions/workflow/status/JosephGabito/roko/code-quality.yml?branch=main&label=PHP%207.4&job=PHP%207.4%20Compatibility)](https://github.com/JosephGabito/roko/actions/workflows/code-quality.yml)
[![Last Commit](https://img.shields.io/github/last-commit/JosephGabito/roko)](https://github.com/JosephGabito/roko/commits/main)
[![Issues](https://img.shields.io/github/issues/JosephGabito/roko)](https://github.com/JosephGabito/roko/issues)
<!-- BADGES-END -->

WordPress plugin - **Development in Progress**

## Development Setup

1. Clone and install dependencies:
```bash
git clone https://github.com/JosephGabito/roko.git
cd roko
composer install
```

## Development Workflow

### Composer Scripts

| Command | Description |
|---------|-------------|
| `composer test` | Run all tests (syntax, compatibility, coding standards, unit tests) |
| `composer phpunit` | Run unit tests only |
| `composer test-unit` | Run unit test suite |
| `composer phpcs` | Check WordPress Coding Standards |
| `composer phpcbf` | Auto-fix coding standards issues |
| `composer php74-compat` | Check PHP 7.4 compatibility |
| `composer php70-compat` | Check PHP 7.0 compatibility |
| `composer syntax-check` | Validate PHP syntax |

### Quick Commands

```bash
# Run all quality checks including unit tests
composer test

# Run just unit tests
composer phpunit

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
- Unit tested with PHPUnit

## Release

```bash
./bin/release.sh 1.0.0
```

---

**Note: This plugin is in active development and not ready for production use.** 