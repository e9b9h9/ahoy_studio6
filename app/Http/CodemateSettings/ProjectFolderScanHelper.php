<?php

namespace App\Http\CodemateSettings;

use DirectoryIterator;

class ProjectFolderScanHelper
{
    /**
     * Scan a folder for top-level files and folders
     * 
     * @param string $path The folder path to scan
     * @return array Array with parent folder info and child items
     */
    public static function scanFolder($path)
    {
        $result = [
            'parent' => null,
            'children' => []
        ];
        
        if (!is_dir($path)) {
            return $result;
        }
        
        // Get parent folder info
        $result['parent'] = [
            'name' => basename($path),
            'path' => $path,
            'is_folder' => true
        ];
        
        try {
            $iterator = new DirectoryIterator($path);
            
            foreach ($iterator as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }
                
                $result['children'][] = [
                    'name' => $fileInfo->getFilename(),
                    'path' => $fileInfo->getPathname(),
                    'is_folder' => $fileInfo->isDir()
                ];
            }
        } catch (\Exception $e) {
            // If folder is not accessible, return parent only
            return $result;
        }
        
        return $result;
    }
}