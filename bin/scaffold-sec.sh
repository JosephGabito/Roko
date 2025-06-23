#!/usr/bin/env bash
set -e

BASE="src/Domain/Security"

# --- Create directory skeleton ---
mkdir -p "${BASE}"/{SecurityKeys,FileSecurity,UserSecurity,NetworkSecurity,FileIntegrity,KnownVulnerabilities}/{Entity,ValueObject,Repository,Service}

# --- Create empty PHP stubs ---
touch \
  "${BASE}/SecurityKeys/Entity/SecurityKeys.php" \
  "${BASE}/SecurityKeys/ValueObject/KeyPair.php" \
  "${BASE}/SecurityKeys/Repository/SecurityKeysRepositoryInterface.php" \
  "${BASE}/SecurityKeys/Service/KeyRotationService.php" \
  "${BASE}/FileSecurity/Entity/FilePermission.php" \
  "${BASE}/FileSecurity/ValueObject/Path.php" \
  "${BASE}/FileSecurity/Repository/FileSecurityRepositoryInterface.php" \
  "${BASE}/FileSecurity/Service/PermissionAuditService.php" \
  "${BASE}/UserSecurity/Entity/UserSecurityProfile.php" \
  "${BASE}/UserSecurity/ValueObject/Username.php" \
  "${BASE}/UserSecurity/ValueObject/FailedLoginCount.php" \
  "${BASE}/UserSecurity/Repository/UserSecurityRepositoryInterface.php" \
  "${BASE}/UserSecurity/Service/PasswordStrengthService.php" \
  "${BASE}/NetworkSecurity/Entity/SslCertificate.php" \
  "${BASE}/NetworkSecurity/ValueObject/DomainName.php" \
  "${BASE}/NetworkSecurity/Repository/NetworkSecurityRepositoryInterface.php" \
  "${BASE}/NetworkSecurity/Service/HstsEnforcementService.php" \
  "${BASE}/FileIntegrity/Entity/IntegrityScan.php" \
  "${BASE}/FileIntegrity/ValueObject/Checksum.php" \
  "${BASE}/FileIntegrity/Repository/FileIntegrityRepositoryInterface.php" \
  "${BASE}/FileIntegrity/Service/IntegrityScanner.php" \
  "${BASE}/KnownVulnerabilities/Entity/Vulnerability.php" \
  "${BASE}/KnownVulnerabilities/ValueObject/CvE.php" \
  "${BASE}/KnownVulnerabilities/Repository/VulnerabilityRepositoryInterface.php" \
  "${BASE}/KnownVulnerabilities/Service/VulnerabilityFeedUpdater.php"

echo "✅ Security domain scaffold created."
