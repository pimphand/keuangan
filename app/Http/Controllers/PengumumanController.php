<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Pengumuman;
use Carbon\Carbon;

class PengumumanController extends Controller
{
    /**
     * Display a listing of announcements for employees
     */
    public function index()
    {
        $user = Auth::user();

        // Get active and current announcements ordered by priority and date
        $pengumuman = Pengumuman::active()
            ->current()
            ->forRole($user->role ?? 'pegawai')
            ->with(['creator'])
            ->orderByRaw("CASE prioritas WHEN 'tinggi' THEN 1 WHEN 'sedang' THEN 2 WHEN 'rendah' THEN 3 ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pegawai.pengumuman', compact('user', 'pengumuman'));
    }

    /**
     * Display a specific announcement
     */
    public function show($id)
    {
        $pengumuman = Pengumuman::with(['creator'])->findOrFail($id);

        // Increment view count
        $pengumuman->incrementViews();

        return view('pegawai.pengumuman-show', compact('pengumuman'));
    }

    /**
     * Get announcements for admin management
     */
    public function adminIndex()
    {
        $pengumuman = Pengumuman::with(['creator', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    /**
     * Show form for creating new announcement
     */
    public function create()
    {
        $users = \App\Models\User::where('role', 'pegawai')->get();

        return view('admin.pengumuman.create', compact('users'));
    }

    /**
     * Store a new announcement
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'user_id' => 'nullable|exists:users,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url'
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/pengumuman', $filename);
            $data['gambar'] = $filename;
        }

        Pengumuman::create($data);

        return redirect()->route('pengumuman.admin.index')
            ->with('success', 'Pengumuman berhasil dibuat!');
    }

    /**
     * Show form for editing announcement
     */
    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $users = \App\Models\User::where('role', 'pegawai')->get();

        return view('admin.pengumuman.edit', compact('pengumuman', 'users'));
    }

    /**
     * Update announcement
     */
    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'user_id' => 'nullable|exists:users,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url'
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($pengumuman->gambar) {
                Storage::delete('public/pengumuman/' . $pengumuman->gambar);
            }

            $file = $request->file('gambar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/pengumuman', $filename);
            $data['gambar'] = $filename;
        }

        $pengumuman->update($data);

        return redirect()->route('pengumuman.admin.index')
            ->with('success', 'Pengumuman berhasil diupdate!');
    }

    /**
     * Delete announcement
     */
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        // Delete image if exists
        if ($pengumuman->gambar) {
            Storage::delete('public/pengumuman/' . $pengumuman->gambar);
        }

        $pengumuman->delete();

        return redirect()->route('pengumuman.admin.index')
            ->with('success', 'Pengumuman berhasil dihapus!');
    }
}
