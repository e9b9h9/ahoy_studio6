<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('folder_files', function (Blueprint $table) {
            $table->dropForeign(['project_folder_id']);
            $table->dropColumn('project_folder_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('folder_files', function (Blueprint $table) {
            $table->foreignId('project_folder_id')->constrained('project_folders')->after('parent_id');
        });
    }
};