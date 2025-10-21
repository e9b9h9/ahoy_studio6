<?php

namespace App\Http\Codemate\Codelines\ExtensionProcessing;

use App\Http\Codemate\Codelines\CommentProcessing\CommentDispatcher;
use App\Http\Codemate\Codelines\CommentProcessing\CommentStrategy;
use App\Http\Codemate\Codelines\CommentProcessing\CommentSyntax;
use Illuminate\Support\Facades\Log;

class ProcessVueFile
{
    public function process($fileData)
    {
        Log::info('ProcessVueFile');
        
				$codelines = $fileData['fileData']['codelines'] ?? [];
				$extension = $fileData['fileData']['metadata']['extension'] ?? null;
        // Send to CommentDispatcher for comment processing
        $commentDispatcher = new CommentDispatcher();
        $fileData = $commentDispatcher->dispatch($fileData, $extension);
        
        return $fileData;
    }

		public function markCodelines($fileData)
		{
			//identify file setup tags:  script , style , template 
			//
		}

		public function codelineCommentRules($fileData)
		{

		}

	




	
		
		
}