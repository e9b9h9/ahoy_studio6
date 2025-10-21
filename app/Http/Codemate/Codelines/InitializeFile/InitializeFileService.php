<?php

namespace App\Http\Codemate\Codelines\InitializeFile;

use App\Models\Codemate\FolderFile;
use App\Models\IndexFile;
use App\Http\Codemate\Codelines\FileProcessing\FileDataGet;
use App\Http\Codemate\Codelines\FileProcessing\FileIndexFindOrCreate;
use App\Http\Codemate\Codelines\ProcessFiles\ProcessFilePipeline;
use App\Http\Codemate\Codelines\FileProcessing\FileContentToCodeline;
use App\Http\Codemate\Codelines\LanguageProcessing\LanguageResolver;
use App\Http\Codemate\Codelines\ExtensionProcessing\ExtensionDispatcher;
use App\Http\Codemate\Codelines\CommentProcessing\CommentDispatcher;
use Illuminate\Support\Facades\Log;

class InitializeFileService
{
    public function initializeFile($fileId)
    {
        $processFile = new ProcessFilePipeline();
        $fileData = $processFile->processFile($fileId);
        $fileData = $this->findOrCreateIndexFile($fileData);
        $fileData = $this->dispatchByExtension($fileData);
        //$fileData = $this->writeInitLogComments($fileData);
				
        return $fileData;
    }


    private function findOrCreateIndexFile($fileData)
    {
        $indexFileService = new FileIndexFindOrCreate();  
        $fileData = $indexFileService->findOrCreateIndexFile($fileData);
        return $fileData;
    }

		private function dispatchByExtension($fileData)
    {
			$extensionDispatcher = new ExtensionDispatcher();
			$fileData = $extensionDispatcher->dispatchFile($fileData);
        return $fileData;
    }

		/*
		private function writeInitLogComments($fileData)
		{
			$initLogComments = new CommentDispatcher();
			$fileData = $initLogComments->writeInitLogComments($fileData);
			return $fileData;
		}
			*/


}