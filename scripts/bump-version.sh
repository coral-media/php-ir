#!/usr/bin/env bash
set -euo pipefail

CURRENT_VERSION=$(jq -r '.version' php-ir/composer.json)

IFS='.' read -r MAJOR MINOR PATCH <<< "$CURRENT_VERSION"

PATCH=$((PATCH + 1))
if [ "$PATCH" -ge 10 ]; then
  PATCH=0
  MINOR=$((MINOR + 1))
fi

NEW_VERSION="$MAJOR.$MINOR.$PATCH"

echo "Bumping version: $CURRENT_VERSION → $NEW_VERSION"

# Update composer.json
jq ".version = \"$NEW_VERSION\"" php-ir/composer.json > /tmp/composer.json
mv /tmp/composer.json php-ir/composer.json

# Update Zephir extensions
for CONFIG in extensions/*/config.json; do
  jq ".version = \"$NEW_VERSION\"" "$CONFIG" > /tmp/config.json
  mv /tmp/config.json "$CONFIG"
done

echo "Version updated to $NEW_VERSION"
