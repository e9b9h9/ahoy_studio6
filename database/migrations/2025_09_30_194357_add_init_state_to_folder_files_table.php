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
            $table->string('init_state')->default('uninitialized')->after('is_mounted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('folder_files', function (Blueprint $table) {
            $table->dropColumn('init_state');
        });
    }
};