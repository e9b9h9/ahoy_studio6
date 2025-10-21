<?php

namespace App\Models\Codemate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class FolderFile extends Model
{
    protected $fillable = [
        'path',
        'name',
        'is_folder',
        'watch',
        'level',
        'has_children',
        'is_mounted',
        'parent_id',
					'extension',
    ];

    protected $casts = [
        'is_folder' => 'boolean',
        'watch' => 'boolean',
        'has_children' => 'boolean',
        'is_mounted' => 'boolean',
    ];

   
    /**
     * Get the parent folder file
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(FolderFile::class, 'parent_id');
    }

    /**
     * Get all child folder files
     */
    public function children(): HasMany
    {
        return $this->hasMany(FolderFile::class, 'parent_id');
    }

    /**
     * Scope to get only folders
     */
    public function scopeFolders($query)
    {
        return $query->where('is_folder', true);
    }

    /**
     * Scope to get only files
     */
    public function scopeFiles($query)
    {
        return $query->where('is_folder', false);
    }

    /**
     * Scope to get watched items
     */
    public function scopeWatched($query)
    {
        return $query->where('watch', true);
    }
}