<?php

namespace App\Http\Codemate\Codelines\ProcessFiles;

use App\Models\Codemate\FolderFile;
use App\Models\IndexFile;
use App\Http\Codemate\Codelines\FileProcessing\FileDataGet;
use App\Http\Codemate\Codelines\FileProcessing\FileIndexFindOrCreate;
use App\Http\Codemate\Codelines\FileProcessing\FileContentToCodeline;
use App\Http\Codemate\Codelines\CodelineProcessing\CodelineProcessingPipeline;
use Illuminate\Support\Facades\Log;

class ProcessFilePipeline
{
    public function processFile($fileId)
    {
        $fileData = $this->buildFileData($fileId);
        
        if ($fileData === null) {
            return null;  // Stop if we couldn't get file data
        }
        
        $fileData = $this->buildCodelines($fileData);
      
        return $fileData;
    }

    private function buildFileData($fileId)
    {
        $fileDataService = new FileDataGet(); 
        $fileData = $fileDataService->getFileData($fileId);
        
        if ($fileData === null) {
            Log::error('Failed to get file data', ['file_id' => $fileId]);
            return null;
        }
        
        return $fileData;
    }

    
    private function buildCodelines($fileData)
    {
        $fileContentToCodeline = new FileContentToCodeline();
        $fileData = $fileContentToCodeline->convertFileContentToCodeline($fileData);
        
        return $fileData;
    }

		

}