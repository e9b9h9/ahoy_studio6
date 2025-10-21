<?php

namespace App\Http\Codemate;

use App\Http\Controllers\Controller;
use App\Models\Codemate\FolderFile;
use App\Http\CodemateSettings\ProjectFolderScanHelper;
use App\Http\CodemateSettings\FolderFileCreateHelper;
use Illuminate\Support\Facades\Log;

class NewFolderFilesHelper extends Controller
{
    public function getNewlyDiscoveredFoldersFiles($foldersToScan)
    {
        $newlyDiscovered = [];
        $nextLevelFolders = [];
        
        while (!empty($foldersToScan)) {
            $currentLevelFolders = [];
            
            // Scan each folder at current level
            foreach ($foldersToScan as $folderPath) {
                if (!is_dir($folderPath)) {
                    continue;
                }
                
                $scannedData = ProjectFolderScanHelper::scanFolder($folderPath);
                
                // Process each scanned child
                foreach ($scannedData['children'] as $child) {
                    $existingFile = FolderFile::where('path', $child['path'])->first();
                    
                    if ($existingFile) {
                        // Path exists in database
                        if ($existingFile->watch == 1) {
                            // Watch is 1, add to next level if it's a folder
                            if ($child['is_folder']) {
                                $currentLevelFolders[] = $child['path'];
                            }
                        }
                        // If watch is 0, we drop it (do nothing)
                    } else {
                        // Path doesn't exist in database - new discovery
                        $newlyDiscovered[] = $child;
                        
                        // Add to next level if it's a folder
                        if ($child['is_folder']) {
                            $currentLevelFolders[] = $child['path'];
                        }
                    }
                }
            }
            
            // Move to next level
            $foldersToScan = $currentLevelFolders;
        }
        
      



         /*
        // Filter for folders only and create FolderFile entries
        $newFolders = array_filter($newlyDiscovered, function($item) {
            return $item['is_folder'] === true;
        });
       
        // Create FolderFile entries for each newly discovered folder
        foreach ($newFolders as $folder) {
            // Format data for FolderFileCreateHelper
            $scanResults = [
                'parent' => $folder,
                'children' => []
            ];
            
            // Call FolderFileCreateHelper with isUserInput=true to set watch=1
            FolderFileCreateHelper::createFromScanResults($scanResults, null, true);
        }

				// Filter for folders only and create FolderFile entries
        $newFiles = array_filter($newlyDiscovered, function($item) {
					return $item['is_folder'] === false;
			});
			
			// Create FolderFile entries for each newly discovered folder
			foreach ($newFiles as $file) {
					// Format data for mounted files
					$folderFileIds = [
							'folder_file_id' => $file['id'],
					];
					
					
			}
        */


        return response()->json($newlyDiscovered);
    }
}