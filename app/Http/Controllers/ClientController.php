<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientTemplateExport;
use App\Imports\ClientImport;

class ClientController extends Controller
{
    /**
     * Normalize phone number format
     * +62 -> 62, 08 -> 62, 62 -> 62
     */
    private function normalizePhoneNumber($phone)
    {
        if (empty($phone)) {
            return $phone;
        }

        // Remove all non-numeric characters except +
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // Handle different formats
        if (strpos($phone, '+62') === 0) {
            // +62 -> 62
            return '62' . substr($phone, 3);
        } elseif (strpos($phone, '08') === 0) {
            // 08 -> 62
            return '62' . substr($phone, 2);
        } elseif (strpos($phone, '62') === 0) {
            // 62 -> 62 (already correct)
            return $phone;
        }

        return $phone;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.client.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.client.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $data = $request->validated();

        // Normalize phone number
        if (isset($data['telepon'])) {
            $data['telepon'] = $this->normalizePhoneNumber($data['telepon']);
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';

            // Create image resource based on file type
            $imageType = exif_imagetype($logo->getPathname());
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($logo->getPathname());
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($logo->getPathname());
                    break;
                case IMAGETYPE_WEBP:
                    $image = imagecreatefromwebp($logo->getPathname());
                    break;
                default:
                    return redirect()->back()
                        ->with('error', 'Format gambar tidak didukung. Gunakan JPEG, PNG, atau WebP.');
            }

            // Convert to WebP and save
            $logoPath = 'gambar/client-logos/' . $logoName;
            $fullPath = public_path($logoPath);

            // Ensure directory exists
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            imagewebp($image, $fullPath, 80); // 80% quality
            imagedestroy($image);

            $data['logo'] = $logoPath;
        }

        Client::create($data);

        return redirect()->route('client.index')
            ->with('success', 'Client berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('admin.client.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('admin.client.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $data = $request->validated();

        // Normalize phone number
        if (isset($data['telepon'])) {
            $data['telepon'] = $this->normalizePhoneNumber($data['telepon']);
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($client->logo && file_exists(public_path($client->logo))) {
                unlink(public_path($client->logo));
            }

            $logo = $request->file('logo');
            $logoName = time() . '_' . pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';

            // Create image resource based on file type
            $imageType = exif_imagetype($logo->getPathname());
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($logo->getPathname());
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($logo->getPathname());
                    break;
                case IMAGETYPE_WEBP:
                    $image = imagecreatefromwebp($logo->getPathname());
                    break;
                default:
                    return redirect()->back()
                        ->with('error', 'Format gambar tidak didukung. Gunakan JPEG, PNG, atau WebP.');
            }

            // Convert to WebP and save
            $logoPath = 'gambar/client-logos/' . $logoName;
            $fullPath = public_path($logoPath);

            // Ensure directory exists
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            imagewebp($image, $fullPath, 80); // 80% quality
            imagedestroy($image);

            $data['logo'] = $logoPath;
        }

        $client->update($data);

        return redirect()->route('client.index')
            ->with('success', 'Client berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        // Delete logo file if exists
        if ($client->logo && file_exists(public_path($client->logo))) {
            unlink(public_path($client->logo));
        }

        $client->delete();

        return redirect()->route('client.index')
            ->with('success', 'Client berhasil dihapus.');
    }

    /**
     * Download client import template
     */
    public function template()
    {
        return Excel::download(new ClientTemplateExport, 'template_client.xlsx');
    }

    /**
     * Import clients from uploaded Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ClientImport, $request->file('file'));
            return redirect()->route('client.index')->with('success', 'Data client berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->route('client.index')->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }
}
