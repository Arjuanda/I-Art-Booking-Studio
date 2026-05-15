<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentationsController extends Controller
{
    public function index() {
        $data_documentation = Documentation::with('postMaker')->latest()->get();
        return view('admin.documentations' , ['title' => 'Documentation','data' => $data_documentation]);
    }

    public function store(Request $request) {
        $request->validate([
            'caption' => 'nullable|string',
            'pictures' => 'nullable|array',
            'pictures.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if (!$request->filled('caption') && !$request->hasFile('pictures')) {
            return back()->withErrors([
                'error' => 'Post harus memiliki caption atau minimal satu gambar.'
            ])->withInput();
        }

        $paths = [];

        if ($request->hasFile('pictures')) {
            foreach ($request->file('pictures') as $file) {
                $paths[] = $file->store('images', 'public');
            }
        }
        $paths = array_filter($paths);

        Documentation::create([
            'user_id' => Auth::id(),
            'caption' => $request->caption,
            'pictures' => $paths
        ]);
        return redirect('/documentations')->with('success', 'Data has been successfully added.');
    }

    public function edit($id) {
        $data_documentation = Documentation::findOrFail($id);

        return response()->json($data_documentation);
    }

    public function update(Request $request, $id) {
        $data_documentation = Documentation::findOrFail($id);

        $request->validate([
            'caption' => 'nullable|string',
            'pictures' => 'nullable|array',
            'pictures.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if (!$request->filled('caption') && !$request->hasFile('pictures') && empty($data_documentation->pictures)) {
            return back()->withErrors([
                'error' => 'Post harus memiliki caption atau minimal satu gambar.'
            ])->withInput();
        }

        $existingPictures = $data_documentation->pictures ?? [];

        if ($request->removedExisting) {
            $removedExisting = json_decode($request->removedExisting, true);
            foreach ($removedExisting as $index) {
                if (isset($existingPictures[$index])) {
                    Storage::delete('public/' . $existingPictures[$index]);
                    unset($existingPictures[$index]);
                }
            }
            $existingPictures = array_values($existingPictures);
        }

        if ($request->hasFile('pictures')) {
            foreach ($request->file('pictures') as $file) {
                $existingPictures[] = $file->store('images', 'public');
            }
        }

        $existingPictures = array_filter($existingPictures);
        if (!$request->filled('caption') && empty($existingPictures)) {
            return back()->withErrors([
                'error' => 'Post harus memiliki caption atau minimal satu gambar.'
            ])->withInput()->with('modal', [
                'type' => 'documentation',
                'action' => 'edit',
                'id' => $id
            ]);
        }

        $data_documentation->update([
            'caption' => $request->caption,
            'comment' => $request->comment,
            'pictures' => $existingPictures,
        ]);

        return redirect('/documentations')->with('success', 'Data has been successfully updated.');
    }

    public function destroy($id) {
        $data_documentation = Documentation::findOrFail($id);

        if ($data_documentation->pictures) {
            foreach ($data_documentation->pictures as $pic) {
                if (Storage::disk('public')->exists($pic)) {
                    Storage::disk('public')->delete($pic);
                }
            }
        }
        $data_documentation->delete();
        return redirect('/documentations')->with('success', 'Data has been successfully deleted');
    }

    public function indexUser() {
        $data_documentation = Documentation::with('postMaker')->latest()->get();
        $user = Auth::user();
        return view('user.documentation', ['title' => 'Documentation','data' => $data_documentation, 'user' => $user]);
    }

}
