<?php

namespace App\Http\Codemate;

use App\Http\Controllers\Controller;
use App\Models\Codemate\FolderFile;
use App\Http\CodemateSettings\FolderFileCreateHelper;

class MountFilesHelper extends Controller
{
    public function mount()
    {
        $folderId = request()->input('folder_id');
        $watchedFiles = $this->scanMountableFolders($folderId);
        $watchedFilesData = $watchedFiles->getData(true);
				$uninitializedFiles = $this->getUninitializedFiles($watchedFilesData);	
				$mountedFiles = $this->mountFiles($uninitializedFiles);
				return $mountedFiles;
	    }

		public function scanMountableFolders($folderId) {
        
			if (!$folderId) {
					return response()->json(['error' => 'No folder ID provided'], 400);
			}
			
			$folder = FolderFile::find($folderId);
			
			if (!$folder || !$folder->is_folder) {
					return response()->json(['error' => 'Invalid folder'], 404);
			}
			
			// Start with the selected folder path
			$foldersToScan = [$folder->path];
			// Call the helper to get newly discovered files
			$helper = new NewFolderFilesHelper();
			$scanResults = $helper->getNewlyDiscoveredFoldersFiles($foldersToScan);
			$newlyDiscovered = $scanResults->getData(true);
			
			// Create FolderFile entries for newly discovered items
			$this->createFolderFilesFromDiscovery($newlyDiscovered, $folderId);
			
			// Get all mountable files
			return $this->getMountableFiles($folderId);
		
	}

	public function createFolderFilesFromDiscovery($scanResults, $folderId) {
		// Get project folder information
		$projectFolder = FolderFile::find($folderId);
		$projectFolderPath = $projectFolder->path;
		
		// Call FolderFileCreateHelper with project context
		FolderFileCreateHelper::createWithoutParentChildGroups($scanResults, $folderId, $projectFolderPath, true);
	}

	public function getMountableFiles($folderId) {
		$folder = FolderFile::find($folderId);
		if (!$folder || !$folder->is_folder) {
			return response()->json(['error' => 'Invalid folder'], 404);
		}
		
		// Use AllFolderFilesWatchedHelper to get all watched files
		$helper = new AllFolderFilesWatchedHelper();
		$watchedFilesResponse = $helper->getWatchedFiles();
		
		// Extract the data from the JSON response
		$watchedFiles = $watchedFilesResponse->getData(true);
		
		return response()->json($watchedFiles);
	}

	public function getUninitializedFiles($watchedFiles) {
		$uninitializedFiles = [];
		foreach ($watchedFiles as $file) {
			if ($file['init_state'] == 'uninitialized') {
				$uninitializedFiles[] = $file;
			}
		}
		return response()->json($uninitializedFiles);
	}

	public function mountFiles($uninitializedFilesResponse) {
		// Extract data from the response
		$uninitializedFiles = $uninitializedFilesResponse->getData(true);
		
		// Collect file IDs to update
		$fileIds = [];
		foreach ($uninitializedFiles as $file) {
			$fileIds[] = $file['id'];
		}
		
		// Update is_mounted to true for all files
		if (!empty($fileIds)) {
			FolderFile::whereIn('id', $fileIds)
				->update(['is_mounted' => true]);
		}
		
		return response()->json($uninitializedFiles);
	}
}