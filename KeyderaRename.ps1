<# 
KeyderaRename.ps1
Rename "Keydera" -> "Keydera" across a project (PowerShell-only).
- Rewrites text files (UTF-8) with safe binary detection
- Optionally renames files and folders (bottom-up)
- Skips common binary files
- Supports dry-run and CSV report

Usage examples (run from project root):
  powershell -ExecutionPolicy Bypass -File .\KeyderaRename.ps1 -Root . -DryRun
  powershell -ExecutionPolicy Bypass -File .\KeyderaRename.ps1 -Root .
  powershell -ExecutionPolicy Bypass -File .\KeyderaRename.ps1 -Root . -SkipRenameNames
  powershell -ExecutionPolicy Bypass -File .\KeyderaRename.ps1 -Root . -OnlyRenameNames
  powershell -ExecutionPolicy Bypass -File .\KeyderaRename.ps1 -Root . -IncludeExt .php,.js,.css,.html
  powershell -ExecutionPolicy Bypass -File .\KeyderaRename.ps1 -Root . -ExcludeDirs storage,uploads,cache
#>

param(
  [string]$Root = ".",
  [switch]$DryRun,
  [switch]$SkipRenameNames,
  [switch]$OnlyRenameNames,
  [string[]]$IncludeExt = @(),
  [string[]]$ExcludeDirs = @(),
  [string]$Report = "keydera_rename_report.csv"
)

$ErrorActionPreference = "Stop"

# Replacements (literal, not regex)
$Replacements = @(
  @{Old="Keydera"; New="Keydera"},
  @{Old="keydera"; New="keydera"},
  @{Old="KEYDERA"; New="KEYDERA"},
  @{Old="Keydera"; New="Keydera"},
  @{Old="keydera"; New="keydera"},
  @{Old="KEYDERA"; New="KEYDERA"},
  @{Old="Keydera"; New="Keydera"},
  @{Old="keydera"; New="keydera"},
  @{Old="KEYDERA"; New="KEYDERA"}
)

# Defaults to skip
$DefaultExcludeDirs = @(".git","node_modules","vendor","dist","build",".svn",".hg",".idea",".vscode",".DS_Store")
$ExcludeSet = New-Object System.Collections.Generic.HashSet[string] ([StringComparer]::OrdinalIgnoreCase)
foreach($d in $DefaultExcludeDirs + $ExcludeDirs){ [void]$ExcludeSet.Add($d) }

# Binary extensions
$BinaryExts = @(
  ".png",".jpg",".jpeg",".gif",".webp",".bmp",".ico",
  ".psd",".ai",".sketch",".pdf",
  ".woff",".woff2",".ttf",".otf",".eot",
  ".zip",".tar",".gz",".bz2",".xz",".7z",".rar",
  ".mp3",".wav",".flac",".ogg",
  ".mp4",".mov",".avi",".mkv",
  ".exe",".dll",".so",".dylib",
  ".pyc",".o",".a",".class"
)

$IncludeExtNorm = @()
if($IncludeExt.Count -gt 0){
  $IncludeExtNorm = $IncludeExt | ForEach-Object { $_.ToLower() }
}

function ShouldSkip([string]$fullPath){
  $parts = $fullPath -split "[/\\]"
  foreach($ex in $ExcludeSet){
    if($parts -contains $ex){ return $true }
  }
  return $false
}

function Test-IsBinaryFile([string]$path){
  $ext = [System.IO.Path]::GetExtension($path).ToLowerInvariant()
  if($BinaryExts -contains $ext){ return $true }
  try{
    $fs = [System.IO.File]::Open($path,[System.IO.FileMode]::Open,[System.IO.FileAccess]::Read,[System.IO.FileShare]::ReadWrite)
    try{
      $len = [Math]::Min(8192, [int]$fs.Length)
      $buf = New-Object byte[] $len
      [void]$fs.Read($buf,0,$len)
      foreach($b in $buf){ if($b -eq 0){ return $true } }
    } finally { $fs.Dispose() }
  } catch { return $true } # if we can't read it safely, treat as binary
  return $false
}

function Replace-InName([string]$name){
  $new = $name
  foreach($r in $Replacements){
    $new = $new.Replace($r.Old, $r.New)
  }
  return $new
}

$Root = (Resolve-Path -LiteralPath $Root).Path

$changes = New-Object System.Collections.Generic.List[object]
$totalRepl = 0

Write-Host "Root: $Root"
if($DryRun){ Write-Host "[DRY-RUN] Preview only. No changes will be written." -ForegroundColor Yellow }

# 1) Content replacements
if(-not $OnlyRenameNames){
  $files = Get-ChildItem -Path $Root -Recurse -File -Force | Where-Object { -not (ShouldSkip $_.FullName) }
  if($IncludeExtNorm.Count -gt 0){
    $files = $files | Where-Object { $IncludeExtNorm -contains $_.Extension.ToLower() }
  }

  foreach($f in $files){
    if(Test-IsBinaryFile $f.FullName){ continue }

    try{
      $text = Get-Content -LiteralPath $f.FullName -Raw -Encoding UTF8
    } catch { continue }

    if ($null -eq $text) { $text = "" }
    $orig = $text
    $perFileCounts = @{}
    foreach($r in $Replacements){
      $oldEsc = [regex]::Escape($r.Old)
      $count = [regex]::Matches($text, $oldEsc).Count
      if($count -gt 0){
        $text = [regex]::Replace($text, $oldEsc, [System.Text.RegularExpressions.MatchEvaluator]{ param($m) $r.New })
        $perFileCounts[$r.Old] = $count
      }
    }

    if($text -ne $orig){
      $fileTotal = ($perFileCounts.Values | Measure-Object -Sum).Sum
      $totalRepl += $fileTotal
      $row = [ordered]@{ File = $f.FullName.Substring($Root.Length).TrimStart("\","/"); "Total Replacements" = $fileTotal }
      foreach($k in $perFileCounts.Keys){ $row[$k] = $perFileCounts[$k] }
      $changes.Add([pscustomobject]$row)
      if(-not $DryRun){
        try{
          Set-Content -LiteralPath $f.FullName -Value $text -Encoding UTF8
        } catch {
          Write-Warning "Could not write $($f.FullName): $_"
        }
      }
    }
  }
}

# 2) Rename files (bottom-up by path length)
if(-not $SkipRenameNames){
  $filesForRename = Get-ChildItem -Path $Root -Recurse -File -Force | Where-Object { -not (ShouldSkip $_.FullName) } | Sort-Object FullName -Descending
  if($IncludeExtNorm.Count -gt 0){
    $filesForRename = $filesForRename | Where-Object { $IncludeExtNorm -contains $_.Extension.ToLower() }
  }

  foreach($f in $filesForRename){
    $newName = Replace-InName $f.Name
    if($newName -ne $f.Name){
      $target = Join-Path $f.DirectoryName $newName
      if(Test-Path -LiteralPath $target){
        $stem = [System.IO.Path]::GetFileNameWithoutExtension($newName)
        $ext  = [System.IO.Path]::GetExtension($newName)
        $i = 2
        do {
          $target = Join-Path $f.DirectoryName ("{0}-{1}{2}" -f $stem,$i,$ext)
          $i++
        } while (Test-Path -LiteralPath $target)
      }
      Write-Host "[RENAME FILE] $($f.FullName) -> $target"
      if(-not $DryRun){
        try{ Rename-Item -LiteralPath $f.FullName -NewName ([System.IO.Path]::GetFileName($target)) -Force }
        catch { Write-Warning "Could not rename file $($f.FullName): $_" }
      }
    }
  }

  # 3) Rename directories (bottom-up)
  $dirs = Get-ChildItem -Path $Root -Recurse -Directory -Force | Where-Object { -not (ShouldSkip $_.FullName) } | Sort-Object FullName -Descending
  foreach($d in $dirs){
    $newName = Replace-InName $d.Name
    if($newName -ne $d.Name){
      $target = Join-Path ([System.IO.Path]::GetDirectoryName($d.FullName)) $newName
      if(Test-Path -LiteralPath $target){
        $i = 2
        do {
          $target = Join-Path ([System.IO.Path]::GetDirectoryName($d.FullName)) ("{0}-{1}" -f $newName,$i)
          $i++
        } while (Test-Path -LiteralPath $target)
      }
      Write-Host "[RENAME DIR ] $($d.FullName) -> $target"
      if(-not $DryRun){
        try{ Rename-Item -LiteralPath $d.FullName -NewName ([System.IO.Path]::GetFileName($target)) -Force }
        catch { Write-Warning "Could not rename dir $($d.FullName): $_" }
      }
    }
  }
}

# 4) Report
if(-not $DryRun){
  try{
    $changes | Export-Csv -Path (Join-Path $Root $Report) -NoTypeInformation -Encoding UTF8
    Write-Host "`n[OK] Report written to $((Join-Path $Root $Report))"
  } catch {
    Write-Warning "Could not write report: $_"
  }
}

Write-Host "[SUMMARY] Total content replacements: $totalRepl"
Write-Host "[DONE]"

