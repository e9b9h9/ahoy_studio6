<?php

namespace App\Http\Codemate\Codelines\CommentProcessing;

class CommentParser
{
    protected array $singleLineOpeners = [];
    protected array $multilineOpeners = [];
    protected array $multilineClosers = [];
    protected array $openerCloserMap = [];

    protected array $collectedComments = [];
    protected array $codelineComments = [];

    /**
     * Constructor - accepts comment syntax configuration
     */
    public function __construct(?array $config = null)
    {
        if ($config) {
            $this->configure($config);
        } else {
            // Default PHP/HTML configuration
            $this->useDefaultConfig();
        }
    }

    /**
     * Configure comment syntax
     */
    public function configure(array $config): self
    {
        $this->singleLineOpeners = $config['single_line'] ?? [];
        $this->openerCloserMap = $config['multiline'] ?? [];
        $this->multilineOpeners = array_keys($this->openerCloserMap);
        $this->multilineClosers = array_values($this->openerCloserMap);

        return $this;
    }

    /**
     * Use default PHP/HTML configuration
     */
    protected function useDefaultConfig(): void
    {
        $this->configure([
            'single_line' => ['//'],
            'multiline' => [
                '<!--' => '-->',
                '/*' => '*/'
            ]
        ]);
    }

    /**
     * Parse file content and categorize comments with target lines
     */
    public function parseWithTargetLines(array $simpleLines, array $originalCodelines): array
    {
        $this->collectedComments = [];
        $this->codelineComments = [];

        $lines = $simpleLines;
        $i = 0;
        $totalLines = count($lines);

        while ($i < $totalLines) {
            $line = $lines[$i];
            $trimmed = trim($line);

            // Check for single line comment at start of line
            if ($this->startsWithSingleLineOpener($trimmed)) {
                $i = $this->handleSingleLineCommentWithTarget($lines, $originalCodelines, $i);
                continue;
            }

            // Check for multiline comment at start of line
            $multilineOpener = $this->startsWithMultilineOpener($trimmed);
            if ($multilineOpener !== false) {
                $i = $this->handleMultilineCommentWithTarget($lines, $originalCodelines, $i, $multilineOpener);
                continue;
            }

            // Check for inline comments (code + comment on same line)
            if ($this->containsSingleLineOpener($line) || $this->containsMultilineOpener($line)) {
                $this->handleCodelineComment($line);
            }

            $i++;
        }

        return [
            'collected_comments' => $this->collectedComments,
            'codeline_comments' => $this->codelineComments
        ];
    }

    /**
     * Parse file content and categorize comments
     */
    public function parse(array $codelines): array
    {
        $this->collectedComments = [];
        $this->codelineComments = [];

        $lines = $codelines;
        $i = 0;
        $totalLines = count($lines);

        while ($i < $totalLines) {
            $line = $lines[$i];
            $trimmed = trim($line);

            // Check for single line comment at start of line
            if ($this->startsWithSingleLineOpener($trimmed)) {
                $i = $this->handleSingleLineComment($lines, $i);
                continue;
            }

            // Check for multiline comment at start of line
            $multilineOpener = $this->startsWithMultilineOpener($trimmed);
            if ($multilineOpener !== false) {
                $i = $this->handleMultilineComment($lines, $i, $multilineOpener);
                continue;
            }

            // Check for inline comments (code + comment on same line)
            if ($this->containsSingleLineOpener($line) || $this->containsMultilineOpener($line)) {
                $this->handleCodelineComment($line);
            }

            $i++;
        }

        return [
            'collected_comments' => $this->collectedComments,
            'codeline_comments' => $this->codelineComments
        ];
    }

    /**
     * Handle single line comments that may become multiline blocks
     */
    protected function handleSingleLineComment(array $lines, int $startIndex): int
    {
        $commentBlock = [];
        $currentIndex = $startIndex;

        // Collect consecutive single line comments
        while ($currentIndex < count($lines)) {
            $trimmed = trim($lines[$currentIndex]);
            
            if ($this->startsWithSingleLineOpener($trimmed)) {
                $commentBlock[] = $lines[$currentIndex];
                $currentIndex++;
            } else {
                break;
            }
        }

        // Determine if single or multiline
        if (count($commentBlock) === 1) {
            $this->collectedComments[] = [
                'type' => 'single_line',
                'lines' => $commentBlock,
                'comment' => $this->extractCommentContent($commentBlock, 'single_line')
            ];
        } else {
            $this->moveCommentsJob($commentBlock, 'consecutive_single_line');
        }

        return $currentIndex;
    }

   
    protected function handleMultilineComment(array $lines, int $startIndex, string $opener): int
    {
        $closer = $this->openerCloserMap[$opener];
        $commentBlock = [];
        $currentIndex = $startIndex;
        $firstLine = trim($lines[$currentIndex]);

        // Check if opener and closer are on the same line
        if ($this->containsCloser($firstLine, $closer)) {
            $commentBlock[] = $lines[$currentIndex];
            $currentIndex++;

            // Check if next line also starts with multiline opener
            if ($currentIndex < count($lines) && $this->startsWithMultilineOpener(trim($lines[$currentIndex])) !== false) {
                // Continue collecting
                return $this->continueMultilineCollection($lines, $currentIndex, $commentBlock);
            } else {
                $this->moveCommentsJob($commentBlock, 'multiline_closed');
                return $currentIndex;
            }
        }

        // Multiline comment spans multiple lines
        $commentBlock[] = $lines[$currentIndex];
        $currentIndex++;

        while ($currentIndex < count($lines)) {
            $commentBlock[] = $lines[$currentIndex];
            
            if ($this->containsCloser($lines[$currentIndex], $closer)) {
                $currentIndex++;
                break;
            }
            $currentIndex++;
        }

        // Check if next line starts with multiline opener
        if ($currentIndex < count($lines) && $this->startsWithMultilineOpener(trim($lines[$currentIndex])) !== false) {
            return $this->continueMultilineCollection($lines, $currentIndex, $commentBlock);
        } else {
            $this->moveCommentsJob($commentBlock, 'multiline_spanning');
            return $currentIndex;
        }
    }

    /**
     * Continue collecting multiline comments
     */
    protected function continueMultilineCollection(array $lines, int $startIndex, array &$existingBlock): int
    {
        $currentIndex = $startIndex;
        
        while ($currentIndex < count($lines)) {
            $trimmed = trim($lines[$currentIndex]);
            $opener = $this->startsWithMultilineOpener($trimmed);
            
            if ($opener !== false) {
                $closer = $this->openerCloserMap[$opener];
                $existingBlock[] = $lines[$currentIndex];
                
                // Check if it closes on same line
                if (!$this->containsCloser($trimmed, $closer)) {
                    // Collect until closer
                    $currentIndex++;
                    while ($currentIndex < count($lines)) {
                        $existingBlock[] = $lines[$currentIndex];
                        if ($this->containsCloser($lines[$currentIndex], $closer)) {
                            $currentIndex++;
                            break;
                        }
                        $currentIndex++;
                    }
                } else {
                    $currentIndex++;
                }
            } else {
                break;
            }
        }

        $this->moveCommentsJob($existingBlock, 'multiline_block');
        return $currentIndex;
    }

    /**
     * Handle comments on the same line as code
     */
    protected function handleCodelineComment(string $line): void
    {
        $comments = [];
        
        // Extract inline single line comments
        foreach ($this->singleLineOpeners as $opener) {
            if (strpos($line, $opener) !== false) {
                $parts = explode($opener, $line, 2);
                if (count($parts) === 2) {
                    $comments[] = [
                        'type' => 'inline_single',
                        'opener' => $opener,
                        'content' => trim($parts[1])
                    ];
                }
            }
        }

        // Extract inline multiline comments
        foreach ($this->multilineOpeners as $opener) {
            $closer = $this->openerCloserMap[$opener];
            $pattern = '/' . preg_quote($opener, '/') . '(.*?)' . preg_quote($closer, '/') . '/';
            
            if (preg_match_all($pattern, $line, $matches)) {
                foreach ($matches[1] as $match) {
                    $comments[] = [
                        'type' => 'inline_multiline',
                        'opener' => $opener,
                        'closer' => $closer,
                        'content' => trim($match)
                    ];
                }
            }
        }

        if (!empty($comments)) {
            $this->codelineComments[] = [
                'line' => $line,
                'comments' => $comments
            ];
        }
    }

    /**
     * Job to process collected comment blocks
     */
    protected function moveCommentsJob(array $commentLines, string $type): void
    {
        $this->collectedComments[] = [
            'type' => $type,
            'lines' => $commentLines,
            'comment' => $this->extractCommentContent($commentLines, $type)
        ];
    }

    /**
     * Check if line starts with single line opener
     */
    protected function startsWithSingleLineOpener(string $line): bool
    {
        foreach ($this->singleLineOpeners as $opener) {
            if (strpos($line, $opener) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if line starts with multiline opener
     */
    protected function startsWithMultilineOpener(string $line): string|false
    {
        foreach ($this->multilineOpeners as $opener) {
            if (strpos($line, $opener) === 0) {
                return $opener;
            }
        }
        return false;
    }

    /**
     * Check if line contains single line opener
     */
    protected function containsSingleLineOpener(string $line): bool
    {
        foreach ($this->singleLineOpeners as $opener) {
            if (strpos($line, $opener) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if line contains multiline opener
     */
    protected function containsMultilineOpener(string $line): bool
    {
        foreach ($this->multilineOpeners as $opener) {
            if (strpos($line, $opener) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if line contains closer
     */
    protected function containsCloser(string $line, string $closer): bool
    {
        return strpos($line, $closer) !== false;
    }

    /**
     * Extract comment content by removing openers/closers and joining lines
     */
    protected function extractCommentContent(array $lines, string $type): string
    {
        $cleanedLines = [];
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            $cleanedLine = $trimmed;
            
            // Remove single line openers
            foreach ($this->singleLineOpeners as $opener) {
                if (strpos($cleanedLine, $opener) === 0) {
                    $cleanedLine = trim(substr($cleanedLine, strlen($opener)));
                    break;
                }
            }
            
            // Remove multiline openers and closers
            foreach ($this->openerCloserMap as $opener => $closer) {
                // Remove opener at start
                if (strpos($cleanedLine, $opener) === 0) {
                    $cleanedLine = trim(substr($cleanedLine, strlen($opener)));
                }
                
                // Remove closer at end
                if (substr($cleanedLine, -strlen($closer)) === $closer) {
                    $cleanedLine = trim(substr($cleanedLine, 0, -strlen($closer)));
                }
            }
            
            if ($cleanedLine !== '') {
                $cleanedLines[] = $cleanedLine;
            }
        }
        
        return implode(' ', $cleanedLines);
    }



    /**
     * Handle single line comments with target codeline
     */
    protected function handleSingleLineCommentWithTarget(array $lines, array $originalCodelines, int $startIndex): int
    {
        $commentBlock = [];
        $lineNumbers = [];
        $currentIndex = $startIndex;

        // Collect consecutive single line comments
        while ($currentIndex < count($lines)) {
            $trimmed = trim($lines[$currentIndex]);
            
            if ($this->startsWithSingleLineOpener($trimmed)) {
                $commentBlock[] = $lines[$currentIndex];
                $lineNumbers[] = $originalCodelines[$currentIndex]['line_number'] ?? $currentIndex + 1;
                $currentIndex++;
            } else {
                break;
            }
        }

        // Find the target codeline (the line after the comment block)
        $targetCodeline = null;
        if ($currentIndex < count($originalCodelines)) {
            $targetCodeline = $originalCodelines[$currentIndex];
        }

        // Determine if single or multiline
        if (count($commentBlock) === 1) {
            $this->collectedComments[] = [
                'type' => 'single_line',
                'lines' => $commentBlock,
                'line_numbers' => $lineNumbers,
                'comment' => $this->extractCommentContent($commentBlock, 'single_line'),
                'target_codeline' => $targetCodeline
            ];
        } else {
            $this->collectedComments[] = [
                'type' => 'consecutive_single_line',
                'lines' => $commentBlock,
                'line_numbers' => $lineNumbers,
                'comment' => $this->extractCommentContent($commentBlock, 'consecutive_single_line'),
                'target_codeline' => $targetCodeline
            ];
        }

        return $currentIndex;
    }

    /**
     * Handle multiline comments with target codeline
     */
    protected function handleMultilineCommentWithTarget(array $lines, array $originalCodelines, int $startIndex, string $opener): int
    {
        $closer = $this->openerCloserMap[$opener];
        $commentBlock = [];
        $lineNumbers = [];
        $currentIndex = $startIndex;
        $firstLine = trim($lines[$currentIndex]);

        // Check if opener and closer are on the same line
        if ($this->containsCloser($firstLine, $closer)) {
            $commentBlock[] = $lines[$currentIndex];
            $lineNumbers[] = $originalCodelines[$currentIndex]['line_number'] ?? $currentIndex + 1;
            $currentIndex++;

            // Check if next line also starts with multiline opener
            if ($currentIndex < count($lines) && $this->startsWithMultilineOpener(trim($lines[$currentIndex])) !== false) {
                // Continue collecting
                return $this->continueMultilineCollectionWithTarget($lines, $originalCodelines, $currentIndex, $commentBlock, $lineNumbers);
            } else {
                // Find target codeline
                $targetCodeline = null;
                if ($currentIndex < count($originalCodelines)) {
                    $targetCodeline = $originalCodelines[$currentIndex];
                }

                $this->collectedComments[] = [
                    'type' => 'multiline_closed',
                    'lines' => $commentBlock,
                    'line_numbers' => $lineNumbers,
                    'comment' => $this->extractCommentContent($commentBlock, 'multiline_closed'),
                    'target_codeline' => $targetCodeline
                ];
                return $currentIndex;
            }
        }

        // Multiline comment spans multiple lines
        $commentBlock[] = $lines[$currentIndex];
        $lineNumbers[] = $originalCodelines[$currentIndex]['line_number'] ?? $currentIndex + 1;
        $currentIndex++;

        while ($currentIndex < count($lines)) {
            $commentBlock[] = $lines[$currentIndex];
            $lineNumbers[] = $originalCodelines[$currentIndex]['line_number'] ?? $currentIndex + 1;
            
            if ($this->containsCloser($lines[$currentIndex], $closer)) {
                $currentIndex++;
                break;
            }
            $currentIndex++;
        }

        // Check if next line starts with multiline opener
        if ($currentIndex < count($lines) && $this->startsWithMultilineOpener(trim($lines[$currentIndex])) !== false) {
            return $this->continueMultilineCollectionWithTarget($lines, $originalCodelines, $currentIndex, $commentBlock, $lineNumbers);
        } else {
            // Find target codeline
            $targetCodeline = null;
            if ($currentIndex < count($originalCodelines)) {
                $targetCodeline = $originalCodelines[$currentIndex];
            }

            $this->collectedComments[] = [
                'type' => 'multiline_spanning',
                'lines' => $commentBlock,
                'line_numbers' => $lineNumbers,
                'comment' => $this->extractCommentContent($commentBlock, 'multiline_spanning'),
                'target_codeline' => $targetCodeline
            ];
            return $currentIndex;
        }
    }

    /**
     * Continue collecting multiline comments with target
     */
    protected function continueMultilineCollectionWithTarget(array $lines, array $originalCodelines, int $startIndex, array &$existingBlock, array &$existingLineNumbers = []): int
    {
        $currentIndex = $startIndex;
        
        while ($currentIndex < count($lines)) {
            $trimmed = trim($lines[$currentIndex]);
            $opener = $this->startsWithMultilineOpener($trimmed);
            
            if ($opener !== false) {
                $closer = $this->openerCloserMap[$opener];
                $existingBlock[] = $lines[$currentIndex];
                $existingLineNumbers[] = $originalCodelines[$currentIndex]['line_number'] ?? $currentIndex + 1;
                
                // Check if it closes on same line
                if (!$this->containsCloser($trimmed, $closer)) {
                    // Collect until closer
                    $currentIndex++;
                    while ($currentIndex < count($lines)) {
                        $existingBlock[] = $lines[$currentIndex];
                        $existingLineNumbers[] = $originalCodelines[$currentIndex]['line_number'] ?? $currentIndex + 1;
                        if ($this->containsCloser($lines[$currentIndex], $closer)) {
                            $currentIndex++;
                            break;
                        }
                        $currentIndex++;
                    }
                } else {
                    $currentIndex++;
                }
            } else {
                break;
            }
        }

        // Find target codeline
        $targetCodeline = null;
        if ($currentIndex < count($originalCodelines)) {
            $targetCodeline = $originalCodelines[$currentIndex];
        }

        $this->collectedComments[] = [
            'type' => 'multiline_block',
            'lines' => $existingBlock,
            'line_numbers' => $existingLineNumbers,
            'comment' => $this->extractCommentContent($existingBlock, 'multiline_block'),
            'target_codeline' => $targetCodeline
        ];
        return $currentIndex;
    }
}
