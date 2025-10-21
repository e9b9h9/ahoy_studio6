<?php

namespace App\Models\Codemate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Feature extends Model
{
    protected $fillable = [
        'feature',
        'folder_file_id',
    ];

    protected $casts = [
        'folder_file_id' => 'integer',
    ];

   
   
}