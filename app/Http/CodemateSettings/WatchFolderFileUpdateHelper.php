<?php

namespace App\Http\CodemateSettings;

use App\Models\Codemate\FolderFile;

class WatchFolderFileUpdateHelper
{
    /**
     * Set folder file watch status to 1 (selected)
     * 
     * @param FolderFile $folderFile The folder file to update
     * @return void
     */
    public static function setWatched(FolderFile $folderFile)
    {
        $folderFile->update([
            'watch' => true
        ]);
        
        // If this is a folder, scan it and create entries for its contents
        if ($folderFile->is_folder) {
            $scanResults = ProjectFolderScanHelper::scanFolder($folderFile->path);
            FolderFileCreateHelper::createFromScanResults($scanResults, $folderFile);
        }
    }
}