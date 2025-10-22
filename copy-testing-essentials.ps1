# PowerShell Script to Copy Essential TestingPage Files
# This script copies only the necessary files for TestingPage to load

# Define source and destination paths
$sourceBase = "C:\Users\emmah\Herd\ahoy_studio6\resources\js"
$destination = "C:\Users\emmah\Herd\ahoy_studio6\storage\app\private"

# Create destination directory if it doesn't exist
if (!(Test-Path -Path $destination)) {
    New-Item -ItemType Directory -Path $destination -Force
    Write-Host "Created destination directory: $destination" -ForegroundColor Green
}

# Define the essential files with their relative paths
$essentialFiles = @(
    # Entry Point
    "pages\codemate\TestingPage.vue",
    
    # Layout Structure
    "features\testing\layouts\SidebarWithTestingLayout.vue",
    "features\testing\TestingWorkspace.vue",
    "features\testing\layouts\WorkspaceHeaderWithThreeColumnLayout.vue",
    
    # Layout Building Blocks
    "features\testing\WorkspaceHeaderContent.vue",
    "features\testing\layouts\ThreeColumnLayout.vue",
    
    # Content Components
    "features\testing\StudioBreadcrumbs.vue",
    "features\testing\HeaderTools.vue",
    "features\testing\NavigationItems.vue",
    "features\testing\MainContent.vue",
    "features\testing\RightPanelContent.vue"
)

Write-Host "Starting copy operation..." -ForegroundColor Yellow
Write-Host "Source: $sourceBase" -ForegroundColor Gray
Write-Host "Destination: $destination" -ForegroundColor Gray
Write-Host ""

$successCount = 0
$errorCount = 0

foreach ($file in $essentialFiles) {
    $sourcePath = Join-Path $sourceBase $file
    $destPath = Join-Path $destination $file
    
    # Create destination subdirectory if it doesn't exist
    $destDir = Split-Path $destPath -Parent
    if (!(Test-Path -Path $destDir)) {
        New-Item -ItemType Directory -Path $destDir -Force | Out-Null
    }
    
    try {
        if (Test-Path -Path $sourcePath) {
            Copy-Item -Path $sourcePath -Destination $destPath -Force
            Write-Host "✓ Copied: $file" -ForegroundColor Green
            $successCount++
        } else {
            Write-Host "✗ File not found: $file" -ForegroundColor Red
            $errorCount++
        }
    } catch {
        Write-Host "✗ Error copying $file : $($_.Exception.Message)" -ForegroundColor Red
        $errorCount++
    }
}

Write-Host ""
Write-Host "Copy operation completed!" -ForegroundColor Yellow
Write-Host "Successfully copied: $successCount files" -ForegroundColor Green
if ($errorCount -gt 0) {
    Write-Host "Errors encountered: $errorCount files" -ForegroundColor Red
}

# List the directory structure created
Write-Host ""
Write-Host "Directory structure created:" -ForegroundColor Cyan
Get-ChildItem -Path $destination -Recurse | ForEach-Object {
    $relativePath = $_.FullName.Substring($destination.Length + 1)
    if ($_.PSIsContainer) {
        Write-Host "📁 $relativePath" -ForegroundColor Blue
    } else {
        Write-Host "📄 $relativePath" -ForegroundColor White
    }
}

Write-Host ""
Write-Host "All essential TestingPage files have been copied to:" -ForegroundColor Green
Write-Host $destination -ForegroundColor Cyan