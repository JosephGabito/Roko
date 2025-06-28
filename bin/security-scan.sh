#!/bin/bash
# Basic security scanning

echo "Running security scans..."

# Check for common security issues
echo "Checking for potential security issues..."

# Look for eval() usage
if grep -r "eval(" src/ --include="*.php"; then
    echo "WARNING: Found eval() usage - review for security"
fi

# Look for file_get_contents with URLs
if grep -r "file_get_contents.*http" src/ --include="*.php"; then
    echo "WARNING: Found file_get_contents with URLs - ensure proper validation"
fi

# Check for SQL queries without prepare
if grep -r "\$wpdb->query.*\$" src/ --include="*.php"; then
    echo "WARNING: Found potential unprepared SQL queries"
fi

echo "Security scan completed" 