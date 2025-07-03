# Roko Security System - Data Flow Architecture

## 📊 Complete Request-Response Flow

```
┌─────────────────────────────────────────────────────────────────────────┐
│                           🌐 VIEW LAYER (Frontend)                      │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  📁 assets/js/security.js                                              │
│  ├─ RokoSecurityDashboard::fetch_security_data()                      │
│  ├─ GET /wp-json/roko/v1/security                                      │
│  ├─ Headers: { 'X-WP-Nonce': nonce }                                   │
│  └─ Renders: sections → checks → badges → async states                │
│                                                                         │
│  📁 assets/js/admin.js                                                 │
│  └─ Alpine.js integration + loading states                            │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ HTTP Request
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                      🎯 PRESENTATION LAYER (REST API)                   │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  📁 src/Infrastructure/WordPress/Security/SecurityJsonService.php      │
│  ├─ register_rest_route('roko/v1', '/security')                        │
│  ├─ permissions_check() → current_user_can('manage_options')           │
│  ├─ handle_request() → securityApplicationService->getSecuritySnapshot()│
│  └─ Returns: JSON response with sections, checks, scores               │
│                                                                         │
│  📁 src/Infrastructure/WordPress/Plugin.php                           │
│  └─ Bootstraps: SecurityJsonService, DI container setup               │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ Method Call
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                     ⚙️ APPLICATION LAYER (Orchestration)                │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  📁 src/Application/Security/SecurityApplicationService.php            │
│  ├─ getSecuritySnapshot()                                              │
│  ├─ ├─ securityAggregate->snapshot()                                   │
│  ├─ ├─ FileSecurityChecks::fromFilePermission()                       │
│  ├─ ├─ SecurityKeysChecks::fromSecurityKeys()                         │
│  ├─ ├─ Adds fix data via SecurityFixMapper                            │
│  ├─ └─ Adds async data via AsyncDeterminationService                  │
│  └─ Returns: Complete security data with scoring                       │
│                                                                         │
│  📁 src/Application/Security/Fix/SecurityFixMapper.php                 │
│  ├─ Maps business codes → fix routes                                   │
│  ├─ 13 fix mappings (debug_on, editor_on, etc.)                       │
│  └─ Returns: { route, needsConfirmation }                             │
│                                                                         │
│  📁 src/Application/Security/Async/AsyncDeterminationService.php       │
│  ├─ determineAsync(checkId, businessCode, evidence)                    │
│  ├─ Only 'log_files' → Async::yes('/wp-json/roko/v1/async/log-files-check')│
│  └─ All others → Async::nope()                                         │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ Domain Calls
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                    🏛️ INFRASTRUCTURE LAYER (WordPress)                  │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  📁 src/Infrastructure/WordPress/Security/WpFileSecurityProvider.php   │
│  ├─ snapshot() → FilePermission entity                                 │
│  ├─ isWpDebugOn(), isEditorOn(), logFilesExposed()                     │
│  ├─ WordPress-specific implementations                                  │
│  └─ Returns: FilePermission with 9 security checks                     │
│                                                                         │
│  📁 src/Infrastructure/WordPress/Security/WpSecurityKeysProvider.php   │
│  ├─ snapshot() → SecurityKeys entity                                   │
│  ├─ getKeySaltInfo(), checkStrength()                                  │
│  ├─ SaltVault integration for Roko-managed keys                        │
│  └─ Returns: SecurityKeys with 8 key/salt pairs                        │
│                                                                         │
│  📁 src/Infrastructure/WordPress/Security/I18n/                        │
│  ├─ FileSecurityI18n::description() → User-friendly descriptions       │
│  ├─ SecurityKeysI18n::description() → Key/salt explanations            │
│  └─ WordPress __() translation functions                               │
│                                                                         │
│  📁 src/Infrastructure/WordPress/Repository/                           │
│  ├─ WpFileIntegrityRepository (core file scanning)                     │
│  ├─ WpVulnerabilityRepository (plugin/theme vulns)                     │
│  └─ Data persistence layer                                             │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ Pure Domain Logic
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                        💎 DOMAIN LAYER (Business Logic)                 │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  📁 src/Domain/Security/SecurityAggregate.php                          │
│  ├─ snapshot() → Complete security analysis                            │
│  ├─ Orchestrates: file security, keys, integrity, vulnerabilities      │
│  ├─ Calculates: weighted scoring, letter grades                        │
│  └─ Returns: JSON schema with sections + meta                          │
│                                                                         │
│  📁 src/Domain/Security/Checks/FileSecurityChecks.php                  │
│  ├─ fromFilePermission() → Domain Service                              │
│  ├─ Maps: FilePermission properties → Check objects                    │
│  ├─ Business logic: status, severity, business codes                   │
│  └─ Returns: Collection of Check value objects                         │
│                                                                         │
│  📁 src/Domain/Security/Checks/SecurityKeysChecks.php                  │
│  ├─ fromSecurityKeys() → Domain Service                                │
│  ├─ Maps: SecurityKey objects → Check objects                          │
│  ├─ Business logic: strength analysis, rotation recommendations        │
│  └─ Returns: Collection of Check value objects                         │
│                                                                         │
│  📁 src/Domain/Security/Checks/ValueObject/                            │
│  ├─ Check.php → Core check structure                                   │
│  │   ├─ Properties: id, label, status, severity, description           │
│  │   ├─ Computed getters: getStatus(), getSeverity()                   │
│  │   └─ Async logic: shows "pending" for async checks                  │
│  ├─ Async.php → Async state management                                 │
│  │   ├─ nope() → Synchronous execution                                 │
│  │   ├─ yes(endpoint) → Asynchronous with endpoint                     │
│  │   └─ toArray() → JSON serialization                                 │
│  ├─ CheckStatus.php → pass, fail, pending                              │
│  └─ Severity.php → critical, high, medium, low, pending                │
│                                                                         │
│  📁 src/Domain/Security/FileSecurity/Entity/                           │
│  ├─ FilePermission.php → Aggregate root                                │
│  └─ Contains: 9 file security value objects                            │
│                                                                         │
│  📁 src/Domain/Security/FileSecurity/ValueObject/                      │
│  ├─ IsWpDebugOn.php, IsEditorOn.php, etc.                             │
│  ├─ LogFilesExposed.php → boolean exposed state                        │
│  └─ SharedFileSecurityDescriptionTrait → common functionality          │
│                                                                         │
│  📁 src/Domain/Security/SecurityKeys/Entity/                           │
│  ├─ SecurityKeys.php → Key management aggregate                        │
│  └─ Contains: Collection of SecurityKey value objects                  │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

## 🔄 Async Flow (log_files example)

```
Frontend                 Application              Domain                 
   │                        │                       │                   
   │ 1. GET /security      │                       │                   
   ├─────────────────────→ │                       │                   
   │                        │ 2. getSnapshot()      │                   
   │                        ├─────────────────────→ │                   
   │                        │                       │ 3. FilePermission  
   │                        │ ←─────────────────────┤ logFilesExposed=true
   │                        │                       │                   
   │                        │ 4. AsyncDetermination │                   
   │                        │ log_files → yes()     │                   
   │                        │                       │                   
   │                        │ 5. Check object       │                   
   │                        │ status = pending      │                   
   │                        │ async.endpoint = /..  │                   
   │ ←─────────────────────┤                       │                   
   │ 6. Render "Pending"    │                       │                   
   │                        │                       │                   
   │ 7. User clicks         │                       │                   
   │ GET /async/log-files   │                       │                   
   ├─────────────────────→ │ 8. Two-step process:  │                   
   │                        │ - glob(*.log)         │                   
   │                        │ - HTTP accessibility  │                   
   │ ←─────────────────────┤ 9. Real results       │                   
   │ 10. Update badge       │                       │                   
```

## 🧩 Key Components by Layer

### 🌐 **View Layer**
- **RokoSecurityDashboard**: Main dashboard controller
- **Loading states**: Spinners, badges, overlays via CSS classes
- **Event handling**: Alpine.js integration, autofix buttons
- **State management**: Local data caching, view preferences

### 🎯 **Presentation Layer** 
- **SecurityJsonService**: REST API controller
- **Route registration**: `/security`, `/security/regenerate-salts`, etc.
- **Permission checks**: `manage_options` capability
- **Error handling**: WP_Error responses, logging

### ⚙️ **Application Layer**
- **SecurityApplicationService**: Main orchestrator
- **SecurityFixMapper**: Business codes → fix routes
- **AsyncDeterminationService**: Performance-based async determination
- **Translation coordination**: Domain → Infrastructure I18n

### 🏛️ **Infrastructure Layer**
- **WpFileSecurityProvider**: WordPress file system checks
- **WpSecurityKeysProvider**: Key/salt management, SaltVault
- **I18n services**: WordPress translation integration
- **Repositories**: Data persistence, external API calls

### 💎 **Domain Layer**
- **SecurityAggregate**: Root aggregate, orchestrates all checks
- **Domain Services**: FileSecurityChecks, SecurityKeysChecks
- **Value Objects**: Check, Async, CheckStatus, Severity
- **Entities**: FilePermission, SecurityKeys
- **Pure business logic**: No WordPress dependencies

## 🎯 Critical Design Decisions

### **Async Determination**
- **Location**: Application layer (not domain)
- **Logic**: Based on CHECK performance, not business operation
- **Current**: Only `log_files` (two-step: glob + HTTP tests)
- **Result**: "Pending" status until real check completes

### **Fix System Integration**
- **Business codes**: Emitted by domain (debug_on, editor_on, etc.)
- **Fix mapping**: Application layer maps codes → REST routes
- **Confirmation**: Application decides which fixes need confirmation
- **Execution**: Frontend AJAX → REST endpoints → WordPress actions

### **Data Flow Guarantees**
- **Separation**: Domain never touches WordPress APIs
- **Translation**: Infrastructure handles WordPress I18n
- **Error handling**: Each layer handles its own error types
- **Caching**: Frontend caches full API responses locally

## 🚀 Request Lifecycle Summary

1. **User loads page** → JavaScript initializes
2. **Fetch security data** → REST API `/wp-json/roko/v1/security`
3. **REST handler** → Application service orchestration  
4. **Domain calculation** → Pure business logic, no side effects
5. **Infrastructure calls** → WordPress-specific implementations
6. **Response assembly** → JSON schema with sections + checks
7. **Frontend rendering** → Badges, async states, fix buttons
8. **User interactions** → Autofix AJAX calls, async triggers
