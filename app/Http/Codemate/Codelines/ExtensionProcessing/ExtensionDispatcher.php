<?php

namespace App\Http\Codemate\Codelines\ExtensionProcessing;

use Illuminate\Support\Facades\Log;
use App\Http\Codemate\Codelines\ExtensionProcessing\ProcessVueFile;
use App\Http\Codemate\Codelines\ExtensionProcessing\ProcessPhpFile;

class ExtensionDispatcher
{
    public function dispatchFile($fileData)
    {
        // Get extension from fileData metadata
        $extension = $fileData['fileData']['metadata']['extension'] ?? null;
        
        if (!$extension) {
            Log::info('No extension found for file', ['file_id' => $fileData['fileData']['file_id'] ?? null]);
            return $fileData;
        }
        
        // Convert to lowercase for consistent matching
        $extension = strtolower($extension);
        
        // Route to appropriate processor based on extension
        switch ($extension) {
            case 'vue':
                return $this->processVueFile($fileData);
            case 'js':
                return $this->processJsFile($fileData);
            case 'php':
                return $this->processPhpFile($fileData);
            default:
                Log::info('No processor for extension', ['extension' => $extension]);
                return $fileData;
        }
    }
    
    private function processVueFile($fileData)
    {
        $processVueFile = new ProcessVueFile();
        return $processVueFile->process($fileData);
    }
    
    private function processJsFile($fileData)
    {
        Log::info('Processing JS file', ['file_id' => $fileData['fileData']['file_id'] ?? null]);
        // JavaScript-specific processing logic here
        return $fileData;
    }
    
    private function processPhpFile($fileData)
    {
        Log::info('Processing PHP file', ['file_id' => $fileData['fileData']['file_id'] ?? null]);
        $processPhpFile = new ProcessPhpFile();
        return $processPhpFile->process($fileData);
    }
}