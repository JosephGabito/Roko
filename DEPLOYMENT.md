# Deployment Checklist

## Pre-Release
- [ ] Run `composer test`
- [ ] Run `composer php74-compat`
- [ ] Update version in `roko.php`
- [ ] Update `CHANGELOG.md`
- [ ] Test on clean WordPress install
- [ ] Check all features work
- [ ] Verify no PHP errors in debug log

## Release
- [ ] Run `./bin/release.sh <version>`
- [ ] Test the generated ZIP file
- [ ] Upload to WordPress.org SVN
- [ ] Create GitHub release
- [ ] Update documentation

## Post-Release
- [ ] Monitor for issues
- [ ] Respond to support requests
- [ ] Plan next version features 