<?php

namespace App\Http\Codemate\Codelines\FileProcessing;

use App\Models\IndexFile;
use Illuminate\Support\Facades\Log;

class FileIndexFindOrCreate
{
    
    public function findOrCreateIndexFile($fileData)
    {
        // Extract common data once at the start
        $fileName = $fileData['fileData']['metadata']['file_name'];
        $contentLength = $fileData['fileData']['metadata']['content_length'];
        
        // Check if existing entry exists
        $fileIndex = $this->checkExistingIndexFile($fileName, $contentLength);
        
        if ($fileIndex) {
            // Found existing - add its ID to fileData
            $fileData['fileData']['index_file_id'] = $fileIndex->id;
        } else {
            // Not found - create a new one (but don't add ID to fileData)
            $this->createIndexFileEntry($fileName, $contentLength);
        }
        
        return $fileData;
    }

    /**
     * Check if an index file entry already exists in the database
     * 
     * @return IndexFile|null Returns the IndexFile model if found, null otherwise
     */
    private function checkExistingIndexFile($fileName, $contentLength)
    {
        try {
            $fileIndex = IndexFile::where('file_name', $fileName)
                ->where('content_length', $contentLength)
                ->first();
            
            if ($fileIndex) {
                Log::info('Index file entry already exists', [
                    'index_file_id' => $fileIndex->id,
                    'file_name' => $fileName,
                    'content_length' => $contentLength
                ]);
            }
            
            return $fileIndex;  // Returns IndexFile model or null
            
        } catch (\Exception $e) {
            Log::error('Failed to check existing index file', [
                'file_name' => $fileName,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    /**
     * Create a new index file entry in the database
     * 
     * @return IndexFile|null Returns the created IndexFile model, or null if creation fails
     */
    private function createIndexFileEntry($fileName, $contentLength)
    {
		
			$extension = pathinfo($fileName, PATHINFO_EXTENSION);
        try {
            $fileIndex = IndexFile::create([
                'file_name' => $fileName,
                'content_length' => $contentLength,
                'languages' => null,
                'project_keywords' => null,
                'extension' => $extension
            ]);
            
            Log::info('Index file entry created', [
                'index_file_id' => $fileIndex->id,
                'file_name' => $fileName
            ]);
            
            return $fileIndex;
            
        } catch (\Exception $e) {
            Log::error('Failed to create index file entry', [
                'file_name' => $fileName,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}