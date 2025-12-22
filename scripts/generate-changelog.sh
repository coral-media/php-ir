#!/usr/bin/env bash
set -euo pipefail

OUTPUT="CHANGELOG.md"

# ------------------------------------------------------------------
# Resolve GitHub repository URL
# ------------------------------------------------------------------
REMOTE_URL=$(git remote get-url origin)

if [[ "$REMOTE_URL" =~ github.com[:/](.+/.+)(\.git)?$ ]]; then
  REPO="${BASH_REMATCH[1]}"
else
  echo "❌ Unable to determine GitHub repo from origin remote"
  exit 1
fi

GITHUB_BASE="https://github.com/$REPO"

# ------------------------------------------------------------------
# Start CHANGELOG
# ------------------------------------------------------------------
echo "# Changelog" > "$OUTPUT"
echo >> "$OUTPUT"

TAGS=($(git tag --sort=creatordate))

if [ "${#TAGS[@]}" -lt 2 ]; then
  echo "⚠️  Not enough tags to generate changelog"
  exit 0
fi

# Iterate newest → oldest
for ((i=${#TAGS[@]}-1; i>0; i--)); do
  CURRENT="${TAGS[$i]}"
  PREVIOUS="${TAGS[$((i-1))]}"

  TAG_URL="$GITHUB_BASE/releases/tag/$CURRENT"

  echo "## [$CURRENT]($TAG_URL)" >> "$OUTPUT"
  echo >> "$OUTPUT"

  git log --oneline "$PREVIOUS..$CURRENT" \
    | while read -r HASH MESSAGE; do
        COMMIT_URL="$GITHUB_BASE/commit/$HASH"
        echo "- [$HASH]($COMMIT_URL) $MESSAGE" >> "$OUTPUT"
      done

  echo >> "$OUTPUT"
done

echo "✔ CHANGELOG.md generated"