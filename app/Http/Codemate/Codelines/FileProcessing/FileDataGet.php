<?php

namespace App\Http\Codemate\Codelines\FileProcessing;

use App\Models\Codemate\FolderFile;
use Illuminate\Support\Facades\Log;

class FileDataGet
{
    public function getFileData($fileId)
    {
        $file = FolderFile::find($fileId);
        
        if (!$this->validateFile($file, $fileId)) {
            return null;
        }
        
        $fileContents = $this->readFileContents($file, $fileId);
        if ($fileContents === null) {
            return null;
        }
        
        $fileMetadata = $this->buildFileMetadata($file);

				$fileMetadata['content_length'] = strlen($fileContents);
        
        return [
					'fileData' => [
						'file_id' => $fileId,
            'contents' => $fileContents,
            'metadata' => $fileMetadata
					]
        ];
    }
    
    private function validateFile($file, $fileId)
    {
        if (!$file || $file->is_folder) {
            Log::info('File not found or is a folder', ['file_id' => $fileId]);
            return false;
        }
        
        if (!file_exists($file->path)) {
            Log::error('File does not exist at path', ['path' => $file->path]);
            return false;
        }
        
        if (!is_readable($file->path)) {
            Log::error('File is not readable', ['path' => $file->path]);
            return false;
        }
        
        return true;
    }
    
    private function readFileContents($file, $fileId)
    {
        try {
            $contents = file_get_contents($file->path);
            return $contents;
        } catch (\Exception $e) {
            Log::error('Failed to read file contents', [
                'file_id' => $fileId,
                'path' => $file->path,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    private function buildFileMetadata($file)
    {
        return [
            'file_name' => $file->name,
            'file_path' => $file->path,
			'extension' => $file->extension ?: pathinfo($file->name, PATHINFO_EXTENSION),
			'is_multilanguage' => false,
			'content_length' => 0,
			'languages' => [],
			'project_keywords' => []
        ];
    }


}