<?php

namespace App\Http\Codemate\Codelines\FileProcessing;

use Illuminate\Support\Facades\Log;
use App\Http\Codemate\Codelines\CodelineProcessing\CodelineNormalizer;

class FileContentToCodeline
{
	public function convertFileContentToCodeline($fileData)
	{
		$fileContents = $fileData['fileData']['contents'];
		$parsedFileContents = $this->parseFileContent($fileContents);
		$codelines = $this->normalizeCodelines($parsedFileContents);
		
		// Add codelines to fileData and return the full structure
		$fileData['fileData']['codelines'] = $codelines;
		return $fileData;
	}

    public function parseFileContent($fileContents)
    {

			$codelines = explode("\n", $fileContents);

			return $codelines;
    }

		public function normalizeCodelines($parsedFileContents)
		{
			$this->codelineNormalizer = new CodelineNormalizer();
			$codelines = $this->codelineNormalizer->normalizeCodelines($parsedFileContents);
			return $codelines;
		}

		
}