<?php

namespace App\Http\CodemateSettings;

use App\Http\Controllers\Controller;
use App\Models\Codemate\FolderFile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FolderFileController extends Controller
{
    public function index()
    {
        return Inertia::render('codemate/CodemateDashboard', [
            'topLevelFolders' => FolderFile::whereNull('parent_id')
                ->where('level', 1)
                ->where('is_folder', true)
                ->with('children.children.children.children.children.children.children.children.children.children')
                ->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        // Scan the folder and create FolderFile entries
        $scanResults = ProjectFolderScanHelper::scanFolder($request->path);
        FolderFileCreateHelper::createFromScanResults($scanResults, null, true);

        return back();
    }

    public function update(Request $request, FolderFile $folderFile)
    {
        if ($request->has('watch') && $request->watch) {
            WatchFolderFileUpdateHelper::setWatched($folderFile);
        }

        return back();
    }
}