#!/usr/bin/env bash

set -euo pipefail

# ---------------------------------------------------------------------------
# Paths (absolute, deterministic)
# ---------------------------------------------------------------------------

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
VERSION_FILE="$ROOT_DIR/.version"
EXTENSIONS_DIR="$ROOT_DIR/extensions"

# ---------------------------------------------------------------------------
# Args
# ---------------------------------------------------------------------------

BUMP_TYPE="${1:-}"
EXTENSIONS_FLAG="${2:-}"

usage() {
  echo "Usage: $0 {major|minor|patch} [--extensions]"
  exit 1
}

if [[ ! "$BUMP_TYPE" =~ ^(major|minor|patch)$ ]]; then
  usage
fi

# ---------------------------------------------------------------------------
# Read & validate version
# ---------------------------------------------------------------------------

if [[ ! -f "$VERSION_FILE" ]]; then
  echo "❌ .version file not found at $VERSION_FILE"
  exit 1
fi

VERSION_RAW="$(tr -d '[:space:]' < "$VERSION_FILE")"

if [[ ! "$VERSION_RAW" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
  echo "❌ Invalid version format in .version: '$VERSION_RAW'"
  echo "Expected: X.Y.Z"
  exit 1
fi

IFS='.' read -r MAJOR MINOR PATCH <<< "$VERSION_RAW"

echo "CURRENT VERSION: $MAJOR.$MINOR.$PATCH"

# ---------------------------------------------------------------------------
# Bump logic (SAFE with set -e)
# ---------------------------------------------------------------------------

case "$BUMP_TYPE" in
  major)
    MAJOR=$((MAJOR + 1))
    MINOR=0
    PATCH=0
    ;;
  minor)
    MINOR=$((MINOR + 1))
    PATCH=0
    ;;
  patch)
    PATCH=$((PATCH + 1))
    ;;
esac

NEW_VERSION="$MAJOR.$MINOR.$PATCH"

echo "NEW VERSION: $NEW_VERSION"

# ---------------------------------------------------------------------------
# Write core version
# ---------------------------------------------------------------------------

echo "$NEW_VERSION" > "$VERSION_FILE"
echo "✔ Updated php-ir/.version"

# ---------------------------------------------------------------------------
# Optional: update Zephir extensions
# ---------------------------------------------------------------------------

if [[ "$EXTENSIONS_FLAG" == "--extensions" ]]; then
  echo "🔧 Updating Zephir extensions"

  if [[ ! -d "$EXTENSIONS_DIR" ]]; then
    echo "⚠️  No extensions directory found, skipping"
  else
    FOUND=0

    for cfg in "$EXTENSIONS_DIR"/*/config.json; do
      [[ -f "$cfg" ]] || continue
      FOUND=1

      echo "  ↳ $cfg"
      jq --arg v "$NEW_VERSION" '.version = $v' "$cfg" > "${cfg}.tmp"
      mv "${cfg}.tmp" "$cfg"
    done

    if [[ "$FOUND" -eq 0 ]]; then
      echo "⚠️  No extension config.json files found"
    else
      echo "✔ Zephir extension versions updated"
    fi
  fi
fi

echo "✅ Done. Version is now $NEW_VERSION"
