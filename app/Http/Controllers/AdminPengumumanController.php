<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Pengumuman;

class AdminPengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pengumuman = Pengumuman::orderBy('created_at', 'desc')->paginate(10);
        return view('app.pengumuman', compact('pengumuman'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'prioritas' => 'required|string',
            'link' => 'nullable|url',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $validated;
        $data['created_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/pengumuman', $filename);
            $data['gambar'] = $filename;
        }

        Pengumuman::create($data);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'prioritas' => 'required|string',
            'link' => 'nullable|url',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $validated;

        if ($request->hasFile('gambar')) {
            if ($pengumuman->gambar) {
                Storage::delete('public/pengumuman/' . $pengumuman->gambar);
            }
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/pengumuman', $filename);
            $data['gambar'] = $filename;
        }

        $pengumuman->update($data);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        if ($pengumuman->gambar) {
            Storage::delete('public/pengumuman/' . $pengumuman->gambar);
        }

        $pengumuman->delete();

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
