#!/bin/bash
# Basic security scanning for Roko WordPress plugin
# Performs static analysis to detect common security issues in PHP code

echo "🔍 Running security scans..."

# Check for common security issues
echo "Checking for potential security issues..."

# Look for eval() usage - dangerous function that executes arbitrary code
echo "• Checking for eval() usage..."
if grep -r "eval(" src/ --include="*.php"; then
    echo "⚠️  WARNING: Found eval() usage - review for security"
else
    echo "✅ No eval() usage found"
fi

# Look for file_get_contents with URLs - potential SSRF vulnerability
echo "• Checking for remote file_get_contents..."
if grep -r "file_get_contents.*http" src/ --include="*.php"; then
    echo "⚠️  WARNING: Found file_get_contents with URLs - ensure proper validation"
else
    echo "✅ No unsafe file_get_contents found"
fi

# Check for SQL queries without prepare - potential SQL injection
echo "• Checking for unprepared SQL queries..."
if grep -r "\$wpdb->query.*\$" src/ --include="*.php"; then
    echo "⚠️  WARNING: Found potential unprepared SQL queries"
else
    echo "✅ No unprepared SQL queries found"
fi

echo "🔍 Security scan completed" 