#!/bin/bash
# Generate WordPress.org readme.txt

cat > readme.txt << 'EOF'
=== Roko Security ===
Contributors: josephgabito
Tags: security, monitoring, file-integrity, vulnerabilities
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.0
Stable tag: trunk
License: GPL v2 or later

Giving your WordPress sites the sanity it needs with comprehensive security monitoring.

== Description ==

Roko Security provides comprehensive security monitoring for your WordPress site including:

* File integrity monitoring
* Vulnerability scanning
* User security profiles
* Security key validation
* Network security checks

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/roko`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Roko Security screen to configure the plugin

== Frequently Asked Questions ==

= Does this plugin slow down my site? =

No, Roko Security is designed to run efficiently in the background.

== Changelog ==

= 1.0.0 =
* Initial release

EOF

echo "readme.txt generated" 