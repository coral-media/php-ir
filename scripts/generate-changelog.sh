#!/usr/bin/env bash
set -euo pipefail

OUTPUT="CHANGELOG.md"

echo "# Changelog" > "$OUTPUT"
echo >> "$OUTPUT"

TAGS=($(git tag --sort=creatordate))

if [ "${#TAGS[@]}" -lt 2 ]; then
  echo "Not enough tags to generate changelog"
  exit 0
fi

for ((i=${#TAGS[@]}-1; i>0; i--)); do
  CURRENT="${TAGS[$i]}"
  PREVIOUS="${TAGS[$((i-1))]}"

  echo "## $CURRENT" >> "$OUTPUT"
  echo >> "$OUTPUT"

  git log --oneline "$PREVIOUS..$CURRENT" \
    | sed 's/^/- /' >> "$OUTPUT"

  echo >> "$OUTPUT"
done

echo "✔ CHANGELOG.md generated"
