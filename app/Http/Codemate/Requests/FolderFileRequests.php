<?php

namespace App\Http\Codemate\Requests;

use App\Http\Controllers\Controller;
use App\Models\Codemate\FolderFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FolderFileRequests extends Controller
{
    public function requestChildren($id, Request $request)
    {
        $filterParameters = $request->query();
        
        $folder = FolderFile::find($id);
        
        if (!$folder) {
            return response()->json([]);
        }
        
        // Get and process only the children, not the parent folder itself
        $children = FolderFile::where('parent_id', $folder->id)->get();
        
        $processedChildren = [];
        
        foreach ($children as $child) {
            $processedChild = $this->processFolder($child, $filterParameters);
            if ($processedChild) {
                $processedChildren[] = $processedChild;
            }
        }
        
        return response()->json($processedChildren);
    }
    
    private function processFolder($folder, $filterParameters)
    {
        // Apply filter parameters to this folder
        if (!$this->applyFilterParameters($folder, $filterParameters)) {
            return null; // Filter failed, don't include this folder
        }
        
        // Get direct children (1 level only)
        $children = FolderFile::where('parent_id', $folder->id)->get();
        
        $processedChildren = [];
        
        foreach ($children as $child) {
            $processedChild = $this->processFolder($child, $filterParameters);
            if ($processedChild) {
                $processedChildren[] = $processedChild;
            }
        }
        
        // Build folder object with filtered children
        return $this->buildFolderObject($folder, $processedChildren);
    }
    
    public function applyFilterParameters($folder, $parameters)
    {
        foreach ($parameters as $key => $value) {
            if ($key === 'is_mounted' && $value == '1') {
                if (!$folder->is_mounted) {
                    return false; // Filter failed
                }
            }
            // Add more filter conditions here as needed
        }
        
        return true; // All filters passed
    }
    
    private function buildFolderObject($folder, $children)
    {
        return [
            'id' => $folder->id,
            'name' => $folder->name,
            'path' => $folder->path,
            'is_folder' => $folder->is_folder,
            'watch' => $folder->watch,
            'level' => $folder->level,
            'has_children' => $folder->has_children,
            'is_mounted' => $folder->is_mounted,
            'init_state' => $folder->init_state,
            'parent_id' => $folder->parent_id,
            'children' => $children
        ];
    }
}