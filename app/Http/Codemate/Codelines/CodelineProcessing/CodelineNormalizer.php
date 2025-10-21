<?php

namespace App\Http\Codemate\Codelines\CodelineProcessing;

use Illuminate\Support\Facades\Log;

class CodelineNormalizer
{
	public function normalizeCodelines($codelines)
	{
		$normalizedCodelines = $this->removeEmptyLines($codelines);
		$normalizedCodelines = $this->orderCodelines($normalizedCodelines);
		return $normalizedCodelines;
	}

    public function removeEmptyLines($codelines)
    {
        $normalizedCodelines = array_filter($codelines, function($codeline) {
            return trim($codeline) !== '';
        });

        return $normalizedCodelines;
    }

		public function orderCodelines($codelines)
		{
			$orderedCodelines = [];
			foreach ($codelines as $lineIndex => $line) {
				$orderedCodelines[] = ['line_number' => $lineIndex + 1, 'codeline' => $line];
			}
			return $orderedCodelines;
		}
}