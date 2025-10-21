<?php

namespace App\Http\Codemate;

use App\Http\Controllers\Controller;
use App\Models\Codemate\FolderFile;

class AllFolderFilesWatchedHelper extends Controller
{
    public function getWatchedFiles()
    {
        $watchedFiles = FolderFile::where('watch', true)
            ->orderBy('level')
            ->orderBy('parent_id', 'asc')
            ->orderBy('name')
            ->get();

        // Build hierarchical structure
        $grouped = $watchedFiles->groupBy('parent_id');
        $result = [];
        
        // Start with top level items (parent_id is null)
        $this->buildHierarchy($grouped, null, $result);
        
        return response()->json($result);
    }

    
    private function buildHierarchy($grouped, $parentId, &$result)
    {
        if (!isset($grouped[$parentId])) {
            return;
        }
        
        foreach ($grouped[$parentId] as $item) {
            $result[] = $item;
            
            // Recursively add children
            $this->buildHierarchy($grouped, $item->id, $result);
        }
    }
}