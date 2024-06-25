<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;

class UploadController extends Controller
{
    public function index() {
        return view('admin.upload.index', [
            'uploads' => Upload::all()
        ]);
    }

    public function show(string $id) {
        return view('admin.upload.show', [
            'upload' => Upload::findOrFail($id)
        ]);
    }

    public function destroy(string $id) {
        $upload = Upload::findOrFail($id);
        $upload->delete();
        return redirect()->route('admin.upload.index');
    }
}
