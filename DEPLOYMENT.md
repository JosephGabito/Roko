# Deployment Checklist

## Pre-Release Testing
- [ ] Run `composer test` (includes all quality checks)
- [ ] Run `composer phpunit` (unit tests)
- [ ] Run `composer php74-compat` (PHP 7.4 compatibility)
- [ ] Run `composer syntax-check` (PHP syntax validation)
- [ ] Run `./bin/security-scan.sh` (security static analysis)
- [ ] Test file integrity system with sample data
- [ ] Verify JavaScript dashboard loads without errors
- [ ] Test security API endpoints (/wp-json/roko/v1/security)
- [ ] Check plugin activation/deactivation hooks
- [ ] Test on clean WordPress install (latest version)
- [ ] Test on WordPress minimum version (5.0)
- [ ] Verify all features work with PHP 7.0+
- [ ] Check for console errors and JavaScript exceptions
- [ ] Test admin interface on mobile/tablet devices

## Code Quality & Security
- [ ] All PHPCS violations resolved (`composer phpcbf`)
- [ ] No PHP warnings/errors in debug log
- [ ] All TODO/FIXME comments addressed or documented
- [ ] Security static analysis passed (`./bin/security-scan.sh`)
- [ ] No hardcoded credentials or sensitive data
- [ ] All user inputs properly sanitized and validated
- [ ] SQL queries use prepared statements
- [ ] File operations have proper permission checks

## Version Management
- [ ] Update version in `roko.php` header
- [ ] Update `CHANGELOG.md` with release notes
- [ ] Generate `readme.txt` with `./bin/generate-readme.sh`
- [ ] Verify version consistency across all files
- [ ] Tag release in Git with proper version

## Performance & Optimization
- [ ] File integrity checks have reasonable timeouts
- [ ] API responses are properly cached where appropriate
- [ ] No unnecessary database queries in admin area
- [ ] JavaScript/CSS assets are optimized
- [ ] Plugin doesn't slow down site loading

## Release Process
- [ ] Run `./bin/release.sh <version>` to create release package
- [ ] Test the generated ZIP file on fresh WordPress install
- [ ] Verify all files are included and none are missing
- [ ] Check file permissions are correct (644 for files, 755 for directories)
- [ ] Upload to WordPress.org SVN repository
- [ ] Create GitHub release with changelog notes
- [ ] Update any external documentation or API docs

## Post-Release Monitoring
- [ ] Monitor WordPress.org support forums for issues
- [ ] Check plugin statistics and download metrics
- [ ] Watch for compatibility reports with other plugins
- [ ] Monitor GitHub issues for bug reports
- [ ] Respond to support requests within 24-48 hours
- [ ] Plan next version features based on user feedback

## Rollback Plan
- [ ] Keep previous stable version available
- [ ] Document quick rollback procedures
- [ ] Have emergency contact information ready
- [ ] Know how to quickly push hotfix if needed

## WordPress.org Specific
- [ ] Plugin adheres to WordPress.org guidelines
- [ ] No premium/paid features (for directory version)
- [ ] Proper GPL licensing and attribution
- [ ] readme.txt follows WordPress.org format
- [ ] Screenshots and banners prepared (if applicable)
- [ ] Plugin description is clear and accurate 