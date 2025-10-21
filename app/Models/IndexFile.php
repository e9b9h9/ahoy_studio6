<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndexFile extends Model
{
    protected $table = 'index_files';

    protected $fillable = [
        'file_name',
        'content_length',
        'languages',
        'project_keywords',
        'extension',
				'is_multilanguage'
    ];

    protected $casts = [
        'languages' => 'array',
        'project_keywords' => 'array',
        'is_multilanguage' => 'boolean'
    ];
}