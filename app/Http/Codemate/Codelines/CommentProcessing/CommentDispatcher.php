<?php

namespace App\Http\Codemate\Codelines\CommentProcessing;

use Illuminate\Support\Facades\Log;
use App\Http\Codemate\Codelines\CommentProcessing\CommentParser;
use App\Http\Codemate\Codelines\CommentProcessing\CommentSyntax;
use App\Http\Codemate\Codelines\CodelineProcessing\CodelineRemover;


class CommentDispatcher
{
    public function dispatch($fileData, $extension)
    {
				$codelines = $fileData['fileData']['codelines'] ?? [];
        $parsedComments = $this->parseComments($codelines, $extension);
				$removedCommentCodelines = $this->removeCommentCodelines($fileData, $parsedComments);
				$fileData = $this->codelinesWithComments($removedCommentCodelines, $parsedComments);
        return $fileData;
    }

		public function parseComments($codelines, $extension)
		{
				// Get comment syntax config for the extension
				$syntaxMethod = $extension ?: 'php';
				$syntaxConfig = CommentSyntax::$syntaxMethod();
				
				// Pass both simple lines and original codelines to parser
				$simpleLines = [];
				foreach ($codelines as $lineObj) {
						$simpleLines[] = $lineObj['codeline'] ?? '';
				}
				
				// Create custom parser that can handle both formats
				$parser = new CommentParser($syntaxConfig);
				$parsedComments = $parser->parseWithTargetLines($simpleLines, $codelines);
				
				// Extract all line numbers from comment parser results
				$extractedLineNumbers = [];
				if (isset($parsedComments['collected_comments'])) {
						foreach ($parsedComments['collected_comments'] as $comment) {
								if (isset($comment['line_numbers'])) {
										$extractedLineNumbers = array_merge($extractedLineNumbers, $comment['line_numbers']);
								}
						}
				}
				
				// Add extracted line numbers to the result
				$parsedComments['extracted_line_numbers'] = $extractedLineNumbers;
				
			
				
				return $parsedComments;
		}

		public function removeCommentCodelines($fileData, $parsedComments)
		{
			$removeLines = $parsedComments['extracted_line_numbers'];
			$commentCodelineRemover = new CodelineRemover();
			$removedCommentCodelines = $commentCodelineRemover->removeCodelines($fileData, $removeLines);
			return $removedCommentCodelines;
		}

		public function codelinesWithComments($fileData, $parsedComments)
		{
			$collectedComments = $parsedComments['collected_comments'] ?? [];
			
			// Format comments as separate section
			$commentsSection = [];
			foreach ($collectedComments as $comment) {
				if (isset($comment['comment']) && isset($comment['target_codeline']['codeline'])) {
					$commentsSection[] = [
						'comment' => $comment['comment'],
						'codeline' => $comment['target_codeline']['codeline']
					];
				}
			}
			
			// Add comments as separate section alongside metadata and codelines
			$fileData['comments'] = $commentsSection;
		
			return $fileData;
		}

		/*
		public function writeInitLogComments($fileData)
		{
			$initCommentLogComments = new InitCommentLogComments();
			$initCommentLogComments->writeInitLogComments($fileData);
			return $fileData;
		}
		*/
		
}