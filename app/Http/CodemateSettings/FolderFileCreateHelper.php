<?php

namespace App\Http\CodemateSettings;

use App\Models\Codemate\FolderFile;

class FolderFileCreateHelper
{
    /**
     * Create FolderFile entries from scan results
     * 
     * @param array $scanResults Array with parent and children from ProjectFolderScanHelper
     * @param FolderFile|null $existingParent Optional existing parent folder to use instead of creating new one
     * @param bool $isUserInput Whether this is from user input (sets watch = true for parent)
     * @return void
     */
    public static function createFromScanResults(array $scanResults, FolderFile $existingParent = null, bool $isUserInput = false)
    {
        // Use existing parent or create new one
        $parentFolderFile = $existingParent;
        
        if (!$parentFolderFile && $scanResults['parent']) {
            $parentFolderFile = FolderFile::create([
                'name' => $scanResults['parent']['name'],
                'path' => $scanResults['parent']['path'],
                'is_folder' => $scanResults['parent']['is_folder'],
                'watch' => $isUserInput ? true : false,
                'level' => 1,
                'parent_id' => null,
                'extension' => null // folders don't have extensions
            ]);
        }
        
        // Then create entries for all children with parent_id
        foreach ($scanResults['children'] as $item) {
            // Extract extension without dot for files
            $extension = null;
            if (!$item['is_folder']) {
                $pathInfo = pathinfo($item['name']);
                $extension = isset($pathInfo['extension']) ? strtolower($pathInfo['extension']) : null;
            }
            
            FolderFile::create([
                'name' => $item['name'],
                'path' => $item['path'],
                'is_folder' => $item['is_folder'],
                'watch' => false,
                'level' => $parentFolderFile ? $parentFolderFile->level + 1 : 2,
                'has_children' => false,
                'parent_id' => $parentFolderFile ? $parentFolderFile->id : null,
                'extension' => $extension
            ]);
        }
        
        // Set has_children = true for parent if children were created
        if ($parentFolderFile && !empty($scanResults['children'])) {
            $parentFolderFile->update(['has_children' => true]);
        }
    }

    public static function createWithoutParentChildGroups(array $listedResults, $folderId, $projectFolderPath, bool $isUserInput = false)
    {
        $familyTree = self::createFamilyTree($listedResults, $projectFolderPath);
        
        // Loop until all items are processed
        while (!empty($familyTree)) {
            // Extract all paths from family tree for database query
            $pathsToSearch = [];
            foreach ($familyTree as $item) {
                $pathsToSearch[] = $item['path'];
                // Also add parent path if it exists
                if ($item['parent_path']) {
                    $pathsToSearch[] = $item['parent_path']['path'];
                }
            }
            
            // Remove duplicates
            $pathsToSearch = array_unique($pathsToSearch);
            
            // Query database for existing FolderFiles with matching paths
            $existingFolderFiles = FolderFile::whereIn('path', $pathsToSearch)->get()->keyBy('path');
            
            // Update family tree with database IDs where found
            $itemsToRemove = [];
            foreach ($familyTree as $index => &$item) {
                // Set folder_file_id if path exists in database
                if (isset($existingFolderFiles[$item['path']])) {
                    $folderFile = $existingFolderFiles[$item['path']];
                    $item['folder_file_id'] = $folderFile->id;
                    
                    // Check if this item has a parent_id and if parent has watch=1
                    if ($folderFile->parent_id !== null) {
                        $parent = FolderFile::find($folderFile->parent_id);
                        if ($parent && $parent->watch != 1) {
                            // Update parent to have watch=1
                            $parent->update(['watch' => 1]);
                        }
                        // Mark this item for removal from family tree
                        $itemsToRemove[] = $index;
                    }
                } else {
                    // Path not found in database
                    // Check if parent_path has an id (parent exists in database)
                    if ($item['parent_path'] && isset($existingFolderFiles[$item['parent_path']['path']])) {
                        $parentFolderFile = $existingFolderFiles[$item['parent_path']['path']];
                        
                        // Determine if it's a folder (check if path ends with common file extensions)
                        $isFolder = !preg_match('/\.[a-zA-Z0-9]+$/', $item['path']);
                        
                        // Extract extension without dot for files
                        $extension = null;
                        if (!$isFolder) {
                            $pathInfo = pathinfo($item['path']);
                            $extension = isset($pathInfo['extension']) ? strtolower($pathInfo['extension']) : null;
                        }
                        
                        // Create new FolderFile entry
                        $newFolderFile = FolderFile::create([
                            'name' => basename($item['path']),
                            'path' => $item['path'],
                            'is_folder' => $isFolder,
                            'watch' => true,
                            'level' => $parentFolderFile->level + 1,
                            'has_children' => false,
                            'parent_id' => $parentFolderFile->id,
                            'extension' => $extension
                        ]);
                        
                        // Update the item with the new ID
                        $item['folder_file_id'] = $newFolderFile->id;
                        
                        // Update parent to have has_children = true
                        $parentFolderFile->update(['has_children' => true]);
                        
                        // Mark this item for removal from family tree
                        $itemsToRemove[] = $index;
                    }
                }
                
                // Set parent_path id if parent exists in database
                if ($item['parent_path'] && isset($existingFolderFiles[$item['parent_path']['path']])) {
                    $item['parent_path']['id'] = $existingFolderFiles[$item['parent_path']['path']]->id;
                }
            }
            
            // Remove items that have been processed
            foreach (array_reverse($itemsToRemove) as $index) {
                unset($familyTree[$index]);
            }
            
            // Re-index array
            $familyTree = array_values($familyTree);
            
            // Break if no items were removed to prevent infinite loop
            if (empty($itemsToRemove)) {
                break;
            }
        }
        
        \Illuminate\Support\Facades\Log::info('familyTree processed completely');
        return [];
    }

    public static function createFamilyTree(array $listedResults, $projectFolderPath)
    {
        // Extract all paths from listedResults into a separate array
        $arrayOfPathsToLoopThrough = [];
        foreach ($listedResults as $item) {
            if (isset($item['path'])) {
                $arrayOfPathsToLoopThrough[] = $item['path'];
            }
        }

        $arrayOfPathsToSort = [];
        $pathObjects = [];

        // Process each path by progressively removing directory parts
        foreach ($arrayOfPathsToLoopThrough as $index => $pathToSort) {
            $currentPath = $pathToSort;			
            while ($currentPath !== '.' && $currentPath !== '/' && $currentPath !== '' && $currentPath !== $projectFolderPath) {
                // Only add if we haven't seen this path before
                if (!isset($pathObjects[$currentPath])) {
                    $parentPath = dirname($currentPath);
                    $parentPathObject = null;
                    
                    // Create parent_path object if parent exists and is not the project folder
                    if ($parentPath !== '.' && $parentPath !== '/' && $parentPath !== '' && $parentPath !== $projectFolderPath) {
                        $parentPathObject = [
                            'path' => $parentPath,
                            'id' => null
                        ];
                    }
                    
                    $pathObjects[$currentPath] = [
                        'folder_file_id' => null,
                        'path' => $currentPath,
                        'parent_path' => $parentPathObject
                    ];
                    
                    $arrayOfPathsToSort[] = $currentPath;
                }
                
                // Remove last part of path
                $currentPath = dirname($currentPath);
                // Safety break to prevent infinite loop
                if (strlen($currentPath) <= 3) {
                    break;
                }
            }
            unset($arrayOfPathsToLoopThrough[$index]);
        }

        // Remove duplicates and sort alphabetically
        $uniquePaths = array_unique($arrayOfPathsToSort);
        sort($uniquePaths);
        
        // Build final family tree with sorted paths
        $familyTree = [];
        foreach ($uniquePaths as $path) {
            $familyTree[] = $pathObjects[$path];
        }

        return $familyTree;
    }

}