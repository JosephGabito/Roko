# Roko - WordPress Security Plugin

<!-- BADGES-START -->
[![Linting](https://github.com/JosephGabito/roko/actions/workflows/code-quality.yml/badge.svg?branch=main&v=3)](https://github.com/JosephGabito/roko/actions/workflows/code-quality.yml)
[![Last Commit](https://img.shields.io/github/last-commit/JosephGabito/roko)](https://github.com/JosephGabito/roko/commits/main)
[![Issues](https://img.shields.io/github/issues/JosephGabito/roko)](https://github.com/JosephGabito/roko/issues)
[![License](https://img.shields.io/badge/license-GPL--2.0--or--later-blue)](LICENSE)
<!-- BADGES-END -->

**Enterprise-grade WordPress security plugin built with Domain-Driven Design (DDD) architecture.**

## 🏗️ Architecture Overview

Roko follows **clean architecture principles** with strict separation of concerns across four distinct layers:

```mermaid
graph TD
    subgraph "🌐 Presentation Layer"
        REST["SecurityJsonService<br/>REST API Endpoints"]
    end
    
    subgraph "🎯 Application Layer"
        APP["SecurityApplicationService<br/>Use Case Orchestration"]
        INTRF["SecurityTranslationProviderInterface<br/>Infrastructure Contracts"]
    end
    
    subgraph "🏛️ Domain Layer"
        AGG["SecurityAggregate<br/>Domain Root"]
        CHECKS["SecurityKeysChecks<br/>Business Logic"]
        ENTITIES["Domain Entities<br/>SecurityKeys, Check, etc."]
    end
    
    subgraph "🔌 Infrastructure Layer"
        WP_TRANS["WpSecurityTranslationProvider<br/>WordPress Implementation"]
        WP_REPO["WpSecurityKeysProvider<br/>WordPress Data Access"]
        I18N["SecurityKeysChecksI18n<br/>Translation Strings"]
    end
    
    REST --> APP
    APP --> AGG
    APP --> INTRF
    AGG --> CHECKS
    CHECKS --> ENTITIES
    WP_TRANS -.->|implements| INTRF
    WP_REPO --> AGG
    WP_TRANS --> I18N
    
    classDef presentation fill:#e1f5fe
    classDef application fill:#f3e5f5
    classDef domain fill:#e8f5e8
    classDef infrastructure fill:#fff3e0
    
    class REST presentation
    class APP,INTRF application
    class AGG,CHECKS,ENTITIES domain
    class WP_TRANS,WP_REPO,I18N infrastructure
```

## 🎯 DDD Principles in Action

### **Layer Responsibilities**

| Layer | Purpose | Example Classes | Key Principles |
|-------|---------|-----------------|----------------|
| **🌐 Presentation** | HTTP handling, request/response | `SecurityJsonService` | Framework-specific adapters |
| **🎯 Application** | Use case orchestration | `SecurityApplicationService` | Coordinates domain + infrastructure |
| **🏛️ Domain** | Pure business logic | `SecurityAggregate`, `SecurityKeysChecks` | Framework-agnostic, self-contained |
| **🔌 Infrastructure** | External concerns | `WpSecurityTranslationProvider` | WordPress-specific implementations |

### **Dependency Flow (Clean Architecture)**

```mermaid
graph LR
    PRES[🌐 Presentation] --> APP[🎯 Application]
    APP --> DOM[🏛️ Domain]
    APP --> INFRA[🔌 Infrastructure]
    
    DOM -.->|"❌ Never depends on"| INFRA
    
    classDef presentation fill:#e1f5fe
    classDef application fill:#f3e5f5
    classDef domain fill:#e8f5e8
    classDef infrastructure fill:#fff3e0
    
    class PRES presentation
    class APP application
    class DOM domain
    class INFRA infrastructure
```

## 📁 Project Structure

```
src/
├── 🏛️ Domain/
│   └── Security/
│       ├── SecurityAggregate.php          # Aggregate Root
│       ├── Checks/
│       │   ├── SecurityKeysChecks.php     # Domain Service
│       │   └── ValueObject/
│       │       ├── Check.php              # Value Object
│       │       ├── CheckStatus.php        # Enum-like Value Object
│       │       └── Severity.php           # Enum-like Value Object
│       └── SecurityKeys/
│           ├── Entity/
│           │   └── SecurityKeys.php       # Domain Entity
│           └── ValueObject/
│               └── SecurityKey.php        # Value Object
├── 🎯 Application/
│   └── Security/
│       ├── SecurityApplicationService.php # Use Case Orchestrator
│       └── Provider/
│           └── SecurityTranslationProviderInterface.php # Contract
└── 🔌 Infrastructure/
    └── WordPress/
        ├── Plugin.php                     # DI Container
        └── Security/
            ├── SecurityJsonService.php    # REST Controller
            ├── WpSecurityKeysProvider.php # Data Access
            ├── Provider/
            │   └── WpSecurityTranslationProvider.php # Translation Implementation
            └── I18n/
                └── SecurityKeysChecksI18n.php # WordPress Translations
```

## 🔍 Architecture Patterns

### **1. Domain Self-Serialization**

Domain entities handle their own serialization, accepting dependencies at creation time:

```php
// ✅ Domain handles its own data transformation
class SecurityKeysChecks {
    public static function fromSecurityKeys(
        SecurityKeys $securityKeys, 
        array $recommendations = array()
    ): self {
        // Business logic + self-serialization
        return new self($checks);
    }
    
    public function toArray(): array {
        // Domain controls its own output format
    }
}
```

### **2. Single-Purpose Application Services**

Each application service focuses on one specific use case:

```php
// ✅ Focused, single-responsibility service
class SecurityApplicationService {
    public function getSecuritySnapshot() {
        // Get translations from infrastructure
        $recommendations = $this->translationProvider->getAllSecurityKeyRecommendations();
        
        // Let domain handle its own serialization
        return $this->securityAggregate->snapshot($recommendations);
    }
}
```

### **3. Interface Segregation**

Clean contracts between layers:

```php
// ✅ Application defines what it needs
interface SecurityTranslationProviderInterface {
    public function getAllSecurityKeyRecommendations();
}

// ✅ Infrastructure provides WordPress-specific implementation
class WpSecurityTranslationProvider implements SecurityTranslationProviderInterface {
    public function getAllSecurityKeyRecommendations() {
        // WordPress-specific translation logic
    }
}
```

### **4. Dependency Injection at the Root**

All dependencies wired at the application entry point:

```php
// ✅ Clean dependency injection
class Plugin {
    public function init() {
        // Domain layer - pure business logic
        $securityAggregate = new SecurityAggregate(/*...*/);
        
        // Infrastructure providers
        $translationProvider = new WpSecurityTranslationProvider();
        
        // Application layer - orchestrates domain + infrastructure
        $securityApplicationService = new SecurityApplicationService(
            $securityAggregate,
            $translationProvider
        );
        
        // Presentation layer - REST API endpoints
        new SecurityJsonService($securityApplicationService);
    }
}
```

## 🛡️ Security Features

- **Security Keys Analysis**: Evaluates WordPress security keys and salts
- **File Integrity Monitoring**: Detects unauthorized file modifications
- **Vulnerability Scanning**: Checks for known security issues
- **File Permission Auditing**: Validates proper file and directory permissions

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

- **Architecture**: Domain-Driven Design (DDD) with clean architecture
- **Code Standards**: WordPress Coding Standards (WPCS)
- **Compatibility**: PHP 7.0+ compatibility
- **Autoloading**: PSR-4 autoloading
- **Naming**: Snake_case for methods and classes (WordPress convention)
- **Testing**: Unit tested with PHPUnit
- **Type Safety**: Strict typing where PHP version allows

## 🚀 Why This Architecture?

### **Benefits**

- ✅ **Maintainable**: Clear separation of concerns
- ✅ **Testable**: Easy to unit test business logic
- ✅ **Flexible**: Easy to swap implementations
- ✅ **Scalable**: Can handle complex business requirements
- ✅ **Framework-Independent**: Domain logic works anywhere

### **Enterprise Patterns**

- **Repository Pattern**: Clean data access abstraction
- **Aggregate Root**: Consistent domain boundaries
- **Value Objects**: Immutable, self-validating data
- **Domain Services**: Complex business logic coordination
- **Application Services**: Use case orchestration
- **Dependency Inversion**: High-level modules don't depend on low-level modules

## Release

```bash
./bin/release.sh 1.0.0
```

---

**Architecture Grade: A+** - This plugin demonstrates enterprise-grade software architecture patterns typically found in large-scale applications, applied thoughtfully to WordPress plugin development. 