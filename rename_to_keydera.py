#!/usr/bin/env python3
"""
rename_to_keydera.py

Safely rename "Keydera" to "Keydera" across a project:
  - Rewrites text file contents (UTF-8, best-effort).
  - Renames files and directories (bottom-up) if names contain target phrases.
  - Skips binary files via extension list + null-byte sniff.
  - Produces a CSV report of replacements per file.
  - Supports dry-run mode.

Usage:
  python3 rename_to_keydera.py --root /path/to/project --dry-run
  python3 rename_to_keydera.py --root /path/to/project
  python3 rename_to_keydera.py --root . --skip-rename-names
  python3 rename_to_keydera.py --root . --only-rename-names
  python3 rename_to_keydera.py --root . --exclude-dirs .git node_modules vendor
  python3 rename_to_keydera.py --root . --report keydera_report.csv

Notes:
  - Always run once with --dry-run to preview.
  - Commit or back up your project before running without --dry-run.
"""

import argparse
import csv
import os
import sys
from pathlib import Path

REPLACEMENTS = [
    ("Keydera", "Keydera"),
    ("keydera", "keydera"),
    ("KEYDERA", "KEYDERA"),
    ("Keydera", "Keydera"),
    ("keydera", "keydera"),
    ("KEYDERA", "KEYDERA"),
    ("Keydera", "Keydera"),
    ("keydera", "keydera"),
    ("KEYDERA", "KEYDERA"),
]

# Extensions to treat as binary and skip (best-effort)
BINARY_EXTS = {
    ".png", ".jpg", ".jpeg", ".gif", ".webp", ".bmp", ".ico",
    ".psd", ".ai", ".sketch", ".pdf",
    ".woff", ".woff2", ".ttf", ".otf", ".eot",
    ".zip", ".tar", ".gz", ".bz2", ".xz", ".7z", ".rar",
    ".mp3", ".wav", ".flac", ".ogg",
    ".mp4", ".mov", ".avi", ".mkv",
    ".exe", ".dll", ".so", ".dylib",
    ".pyc", ".o", ".a", ".class",
}

DEFAULT_EXCLUDE_DIRS = {".git", ".svn", ".hg", ".DS_Store", "node_modules", "vendor", "dist", "build", ".idea", ".vscode"}

def is_text_file(path: Path) -> bool:
    if path.suffix.lower() in BINARY_EXTS:
        return False
    try:
        with open(path, "rb") as f:
            chunk = f.read(8192)
            if b"\x00" in chunk:
                return False
    except Exception:
        return False
    return True

def replace_in_name(name: str) -> str:
    new_name = name
    for old, new in REPLACEMENTS:
        new_name = new_name.replace(old, new)
    return new_name

def should_skip_dir(dirname: str, exclude_dirs: set[str]) -> bool:
    return dirname in exclude_dirs

def main():
    ap = argparse.ArgumentParser(description="Rename Keydera -> Keydera across a project")
    ap.add_argument("--root", type=str, default=".", help="Project root directory")
    ap.add_argument("--dry-run", action="store_true", help="Preview changes only (no writes)")
    ap.add_argument("--report", type=str, default="keydera_rename_report.csv", help="CSV report path")
    ap.add_argument("--skip-rename-names", action="store_true", help="Do not rename file/folder names")
    ap.add_argument("--only-rename-names", action="store_true", help="Only rename names; skip content changes")
    ap.add_argument("--exclude-dirs", nargs="*", default=[], help="Extra directory names to exclude (in addition to defaults)")
    ap.add_argument("--include-ext", nargs="*", default=[], help="If set, only process files with these extensions (e.g., .php .js .css .html)")
    args = ap.parse_args()

    if args.skip_rename_names and args.only_rename_names:
        print("Choose either --skip-rename-names or --only-rename-names, not both.", file=sys.stderr)
        sys.exit(2)

    root = Path(args.root).resolve()
    if not root.exists() or not root.is_dir():
        print(f"Root directory not found: {root}", file=sys.stderr)
        sys.exit(1)

    exclude_dirs = DEFAULT_EXCLUDE_DIRS.union(set(args.exclude_dirs))

    changes = []
    total_replacements_global = 0

    # 1) Content replacements
    if not args.only_rename_names:
        for dirpath, dirnames, filenames in os.walk(root):
            # filter excluded dirs in-place (top-down walk)
            dirnames[:] = [d for d in dirnames if not should_skip_dir(d, exclude_dirs)]

            for fname in filenames:
                fpath = Path(dirpath) / fname
                if args.include_ext and fpath.suffix.lower() not in {e.lower() for e in args.include_ext}:
                    continue
                if not is_text_file(fpath):
                    continue

                try:
                    text = fpath.read_text(encoding="utf-8", errors="ignore")
                except Exception:
                    continue

                per_file_counts: dict[str, int] = {}
                original_text = text
                for old, new in REPLACEMENTS:
                    count = text.count(old)
                    if count:
                        text = text.replace(old, new)
                        per_file_counts[old] = count

                if text != original_text:
                    total_for_file = sum(per_file_counts.values())
                    total_replacements_global += total_for_file
                    changes.append({
                        "File": str(fpath.relative_to(root)),
                        "Total Replacements": total_for_file,
                        **per_file_counts
                    })
                    if not args.dry_run:
                        try:
                            fpath.write_text(text, encoding="utf-8", errors="ignore")
                        except Exception as e:
                            print(f"[WARN] Could not write {fpath}: {e}", file=sys.stderr)

    # 2) Rename files and directories (bottom-up) where names contain target strings
    if not args.skip_rename_names:
        for dirpath, dirnames, filenames in os.walk(root, topdown=False):
            # Skip excluded dirs here too
            dirnames[:] = [d for d in dirnames if not should_skip_dir(d, exclude_dirs)]

            # files
            for fname in filenames:
                old_path = Path(dirpath) / fname
                if args.include_ext and old_path.suffix.lower() not in {e.lower() for e in args.include_ext}:
                    # still consider renaming names even if ext not included? We will honor include-ext strictly.
                    continue
                new_name = replace_in_name(fname)
                if new_name != fname:
                    new_path = Path(dirpath) / new_name
                    if new_path.exists():
                        # disambiguate
                        stem = new_path.stem
                        suffix = new_path.suffix
                        i = 2
                        while (Path(dirpath) / f"{stem}-{i}{suffix}").exists():
                            i += 1
                        new_path = Path(dirpath) / f"{stem}-{i}{suffix}"
                    print(f"[RENAME] {old_path.relative_to(root)} -> {new_path.relative_to(root)}")
                    if not args.dry_run:
                        try:
                            old_path.rename(new_path)
                        except Exception as e:
                            print(f"[WARN] Could not rename file {old_path}: {e}", file=sys.stderr)

            # dirs
            for dname in dirnames:
                old_dir = Path(dirpath) / dname
                new_name = replace_in_name(dname)
                if new_name != dname:
                    new_dir = Path(dirpath) / new_name
                    if new_dir.exists():
                        i = 2
                        while (Path(dirpath) / f"{new_name}-{i}").exists():
                            i += 1
                        new_dir = Path(dirpath) / f"{new_name}-{i}"
                    print(f"[RENAME] {old_dir.relative_to(root)} -> {new_dir.relative_to(root)}")
                    if not args.dry_run:
                        try:
                            old_dir.rename(new_dir)
                        except Exception as e:
                            print(f"[WARN] Could not rename dir {old_dir}: {e}", file=sys.stderr)

    # 3) Write CSV report
    # Normalize all possible columns
    all_keys = {"File", "Total Replacements"}
    for row in changes:
        all_keys.update(row.keys())
    cols = ["File", "Total Replacements"] + sorted([c for c in all_keys if c not in {"File", "Total Replacements"}])

    if not args.dry_run:
        try:
            with open(args.report, "w", newline="", encoding="utf-8") as f:
                writer = csv.DictWriter(f, fieldnames=cols)
                writer.writeheader()
                for row in changes:
                    writer.writerow({k: row.get(k, 0) for k in cols})
            print(f"\n[OK] Wrote report: {args.report}")
        except Exception as e:
            print(f"[WARN] Could not write report: {e}", file=sys.stderr)
    else:
        print("\n[DRY-RUN] No files modified. Run without --dry-run to apply changes.")

    print(f"[SUMMARY] Total content replacements: {total_replacements_global}")
    print("[DONE]")

if __name__ == "__main__":
    main()

