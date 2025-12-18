#!/usr/bin/env bash
set -e

if git describe --tags --exact-match >/dev/null 2>&1; then
  echo "❌ You are pushing a tag without bumping versions first."
  echo "Run:"
  echo "  ./scripts/bump-version.sh"
  echo "  git commit -am 'chore: bump version'"
  exit 1
fi
