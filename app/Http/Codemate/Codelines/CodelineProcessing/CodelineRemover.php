<?php

namespace App\Http\Codemate\Codelines\CodelineProcessing;

use Illuminate\Support\Facades\Log;

class CodelineRemover
{
    /**
     * Remove specified codelines from fileData
     * 
     * @param array $fileData - The complete file data structure
     * @param array $removeLines - Array of line numbers to remove
     * @return array - Modified fileData with specified codelines removed
     */
    public function removeCodelines(array $fileData, array $removeLines): array
    {
      

        // Get existing codelines
        $codelines = $fileData['fileData']['codelines'] ?? [];
        
        // Filter out codelines with specified line numbers
        $filteredCodelines = [];
        foreach ($codelines as $codelineObj) {
            $lineNumber = $codelineObj['line_number'] ?? null;
            
            // Keep codeline if its line number is not in the remove list
            if (!in_array($lineNumber, $removeLines)) {
                $filteredCodelines[] = $codelineObj;
            }
        }
        
        // Update fileData with filtered codelines
        $fileData['fileData']['codelines'] = $filteredCodelines;
      
        
        return $fileData;
    }
}