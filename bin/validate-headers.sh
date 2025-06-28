#!/bin/bash
# Validate WordPress plugin headers

PLUGIN_FILE="roko.php"

echo "Validating WordPress plugin headers..."

# Check required headers
if ! grep -q "Plugin Name:" "$PLUGIN_FILE"; then
    echo "ERROR: Missing 'Plugin Name' header"
    exit 1
fi

if ! grep -q "Description:" "$PLUGIN_FILE"; then
    echo "ERROR: Missing 'Description' header"
    exit 1
fi

if ! grep -q "Version:" "$PLUGIN_FILE"; then
    echo "ERROR: Missing 'Version' header"
    exit 1
fi

if ! grep -q "Author:" "$PLUGIN_FILE"; then
    echo "ERROR: Missing 'Author' header"
    exit 1
fi

echo "Plugin headers validation passed!" 