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
        Schema::create('folder_files', function (Blueprint $table) {
            $table->id();
						$table->string('path');
						$table->string('name');
						$table->boolean('is_folder')->default(false);
						$table->boolean('watch')->nullable()->default(null);
						$table->unsignedBigInteger('parent_id');
						$table->foreign('parent_id')->references('id')->on('folder_files');
						$table->unsignedBigInteger('project_folder_id');
						$table->foreign('project_folder_id')->references('id')->on('project_folders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folder_files');
    }
};
