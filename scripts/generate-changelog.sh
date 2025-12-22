#!/usr/bin/env bash
set -euo pipefail

OUTPUT="CHANGELOG.md"

# ------------------------------------------------------------------
# Resolve GitHub repository base URL from origin (SSH or HTTPS)
# ------------------------------------------------------------------
REMOTE_URL="$(git remote get-url origin)"

# Normalize:
# - git@github.com:owner/repo.git
# - https://github.com/owner/repo.git
# - https://github.com/owner/repo
if [[ "$REMOTE_URL" =~ ^git@github\.com:(.+)\.git$ ]]; then
  REPO="${BASH_REMATCH[1]}"
elif [[ "$REMOTE_URL" =~ ^git@github\.com:(.+)$ ]]; then
  REPO="${BASH_REMATCH[1]}"
elif [[ "$REMOTE_URL" =~ ^https://github\.com/(.+)\.git$ ]]; then
  REPO="${BASH_REMATCH[1]}"
elif [[ "$REMOTE_URL" =~ ^https://github\.com/(.+)$ ]]; then
  REPO="${BASH_REMATCH[1]}"
else
  echo "❌ Unsupported origin remote URL: $REMOTE_URL"
  echo "   Expected GitHub SSH/HTTPS remote."
  exit 1
fi

GITHUB_BASE="https://github.com/$REPO"

# ------------------------------------------------------------------
# Start CHANGELOG
# ------------------------------------------------------------------
{
  echo "# Changelog"
  echo
} > "$OUTPUT"

mapfile -t TAGS < <(git tag --sort=creatordate)

if ((${#TAGS[@]} < 2)); then
  echo "⚠️  Not enough tags to generate changelog" >&2
  exit 0
fi

# Iterate newest → oldest
for ((i=${#TAGS[@]}-1; i>0; i--)); do
  CURRENT="${TAGS[$i]}"
  PREVIOUS="${TAGS[$((i-1))]}"

  TAG_URL="$GITHUB_BASE/releases/tag/$CURRENT"

  {
    echo "## [$CURRENT]($TAG_URL)"
    echo
  } >> "$OUTPUT"

  # Use full hash + subject; shorten display to 7 chars; link uses full hash
  git log --format='%H %s' "$PREVIOUS..$CURRENT" \
    | while IFS= read -r line; do
        HASH="${line%% *}"
        SUBJECT="${line#* }"
        SHORT="${HASH:0:7}"
        COMMIT_URL="$GITHUB_BASE/commit/$HASH"
        echo "- [$SHORT]($COMMIT_URL) $SUBJECT" >> "$OUTPUT"
      done

  echo >> "$OUTPUT"
done

echo "✔ CHANGELOG.md generated at $OUTPUT"
