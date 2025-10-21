<?php

namespace App\Http\Codemate;

use App\Http\Controllers\Controller;
use App\Models\Codemate\FolderFile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CodemateController extends Controller
{
    public function index()
    {
        return Inertia::render('codemate/Codemate', [
            'topLevelFolders' => FolderFile::whereNull('parent_id')
                ->where('level', 1)
                ->where('is_folder', true)
                ->get(),
							
    
        ]);
    }
    
}