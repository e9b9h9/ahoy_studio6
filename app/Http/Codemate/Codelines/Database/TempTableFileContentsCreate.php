<?php

namespace App\Http\Codemate\Codelines\Database;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;

class TempTableFileContentsCreate
{
    public function createTable($fileId)
    {
        $tableName = "init_{$fileId}_contents";
        
        if (Schema::hasTable($tableName)) {
            Log::info('Table already exists', ['table' => $tableName]);
            return;
        }
        
        try {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->text('codeline')->nullable();
                $table->string('language')->nullable();
                $table->text('comment')->nullable();
                $table->integer('order')->nullable();
                $table->timestamps();
            });
            
            Log::info('Table created successfully', ['table' => $tableName]);
            
        } catch (\Exception $e) {
            Log::error('Failed to create table', [
                'table' => $tableName,
                'error' => $e->getMessage()
            ]);
        }
    }
}