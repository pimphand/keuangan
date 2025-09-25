<?php

namespace App\Http\Controllers;

use App\Models\Brosur;
use App\Models\KategoriBrosur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BrosurController extends Controller
{
    /**
     * Process and save image as WebP format
     */
    private function processImage($file, $nama)
    {
        $gambarName = time() . '_' . Str::slug($nama) . '.webp';
        $gambarPath = public_path('gambar/brosur/' . $gambarName);

        // Create directory if it doesn't exist
        if (!file_exists(dirname($gambarPath))) {
            mkdir(dirname($gambarPath), 0755, true);
        }

        // Process image with Intervention Image v3
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        $image->toWebp(80)->save($gambarPath);

        return $gambarName;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brosurs = Brosur::with('kategoriBrosur')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('app.brosur.index', compact('brosurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoriBrosurs = KategoriBrosur::all();
        return view('app.brosur.create', compact('kategoriBrosurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'kategori_brosur_id' => 'nullable|exists:kategori_brosurs,id',
            'harga' => 'required|integer|min:0',
            'spesifikasi_key' => 'nullable|array',
            'tags' => 'nullable|array',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $data = $request->all();
        // Handle spesifikasi data
        if ($request->has('spesifikasi_key')) {
            $filtered = array_filter($request->spesifikasi_key, function ($value) {
                return !is_null($value) && $value !== '';
            });

            if (!empty($filtered)) {
                $data['spesifikasi'] = array_values($filtered);
            }
        }

        $data['tag'] = $request->tags ?? [];
        // Remove the old spesifikasi_key and spesifikasi_value from data
        unset($data['spesifikasi_key'], $data['tags']);

        // Handle gambar upload
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $gambarName = $this->processImage($gambar, $request->nama);
            $data['gambar'] = $gambarName;
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . Str::slug($request->nama) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('files/brosur');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);
            $data['file'] = $fileName;
        }

        Brosur::create($data);

        return redirect()->route('brosur.index')
            ->with('success', 'Brosur berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brosur $brosur)
    {
        $brosur->load('kategoriBrosur');
        return view('app.brosur.show', compact('brosur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brosur $brosur)
    {
        $kategoriBrosurs = KategoriBrosur::all();
        return view('app.brosur.edit', compact('brosur', 'kategoriBrosurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brosur $brosur)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'kategori_brosur_id' => 'nullable|exists:kategori_brosurs,id',
            'harga' => 'required|integer|min:0',
            'spesifikasi_key' => 'nullable|array',
            'tags' => 'nullable|array',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $data = $request->all();

        // Handle spesifikasi data
        if ($request->has('spesifikasi_key')) {

            $filtered = array_filter($request->spesifikasi_key, function ($value) {
                return !is_null($value) && $value !== '';
            });

            if (!empty($filtered)) {
                $data['spesifikasi'] = $request->spesifikasi_key ? array_values($filtered) : null;
            } else {
                $data['spesifikasi'] = null; // Clear spesifikasi if all keys are empty
            }
        }

        $data['tag'] = $request->tags ?? [];

        // Remove the old spesifikasi_key and spesifikasi_value from data
        unset($data['spesifikasi_key']);

        // Handle gambar upload
        if ($request->hasFile('gambar')) {
            // Delete old gambar
            if ($brosur->gambar) {
                $oldImagePath = public_path('gambar/brosur/' . $brosur->gambar);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $gambar = $request->file('gambar');
            $gambarName = $this->processImage($gambar, $request->nama);
            $data['gambar'] = $gambarName;
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($brosur->file) {
                $oldFilePath = public_path('files/brosur/' . $brosur->file);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $file = $request->file('file');
            $fileName = time() . '_' . Str::slug($request->nama) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('files/brosur');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);
            $data['file'] = $fileName;
        }

        $brosur->update($data);

        return redirect()->route('brosur.index')
            ->with('success', 'Brosur berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brosur $brosur)
    {
        // Delete associated files
        if ($brosur->gambar) {
            $imagePath = public_path('gambar/brosur/' . $brosur->gambar);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($brosur->file) {
            $filePath = public_path('files/brosur/' . $brosur->file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $brosur->delete();

        return redirect()->route('brosur.index')
            ->with('success', 'Brosur berhasil dihapus.');
    }
}
