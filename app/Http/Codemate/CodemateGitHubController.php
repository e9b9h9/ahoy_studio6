<?php

namespace App\Http\Codemate;

use App\Http\Controllers\Controller;
use App\Models\Codemate\FolderFile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CodemateGitHubController extends Controller
{
    public function index()
    {
        return Inertia::render('codemate/CodemateGitHub');
    }
    
}