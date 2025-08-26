#!/usr/bin/env bash
set -euo pipefail
src_dir="application/libraries/api_helper_samples"
dst_dir="application/helpers/integrations"
if [ -d "$src_dir" ]; then
  mkdir -p "$dst_dir"
  shopt -s nullglob
  for f in "$src_dir"/*; do
    base="$(basename "$f")"
    mv -f "$f" "$dst_dir/$base"
  done
  rmdir "$src_dir" 2>/dev/null || true
fi
# Optional flatten for CI3 helper loading
if [ "${FLATTEN_HELPERS:-0}" = "1" ] && [ -d "$dst_dir" ]; then
  for f in "$dst_dir"/*; do
    base="$(basename "$f")"
    # only rename php files, delete others
    if [[ "$base" == *.php ]]; then
        new="application/helpers/integration_${base}"
        mv -f "$f" "$new"
    else
        rm -f "$f"
    fi
  done
  rmdir "$dst_dir" 2>/dev/null || true
fi
exit 0
