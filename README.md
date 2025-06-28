# Super Secret WordPress Plugin

<!-- BADGES-START -->
[![Code Quality](https://github.com/JosephGabito/roko/actions/workflows/code-quality.yml/badge.svg?branch=main)](https://github.com/JosephGabito/roko/actions/workflows/code-quality.yml)
[![PHP Version](https://img.shields.io/badge/PHP-7.0%2B-blue)](https://php.net)
[![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue)](https://wordpress.org)
[![License](https://img.shields.io/badge/license-GPL--2.0--or--later-blue)](LICENSE)
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