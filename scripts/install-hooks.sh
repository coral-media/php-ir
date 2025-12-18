#!/usr/bin/env bash
set -e

HOOKS_DIR="$(git rev-parse --git-path hooks)"

mkdir -p "$HOOKS_DIR"

for hook in hooks/*; do
  chmod +x "$(pwd)/$hook"
  ln -sf "$(pwd)/$hook" "$HOOKS_DIR/$(basename "$hook")"
done

echo "✔ Git hooks installed"
