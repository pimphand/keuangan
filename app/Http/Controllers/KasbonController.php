<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKasbonRequest;
use App\Http\Requests\UpdateKasbonRequest;
use App\Models\Kasbon;
use App\Models\User;
use App\Services\ImageProcessingService;
use App\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasbonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kasbon::with(['user', 'disetujui']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan user (untuk non-admin)
        if (auth()->user()->level !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        $kasbons = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('kasbon.index', compact('kasbons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        return view('kasbon.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKasbonRequest $request)
    {
        try {
            $user = Auth::user();
            $saldo = $user->saldo - $request->nominal;

            if ($saldo < 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Saldo tidak cukup.');
            }

            DB::transaction(function () use ($request) {
                $kasbon = Kasbon::create([
                    'user_id' => auth()->id(),
                    'nominal' => $request->nominal,
                    'keterangan' => $request->keterangan,
                    'status' => Kasbon::STATUS_PENDING,
                ]);

                Auth::user()->decrement('saldo', $request->nominal);
            });

            return redirect()->route('pegawai.kasbon')
                ->with('success', 'Pengajuan kasbon berhasil dikirim. Menunggu persetujuan admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengajukan kasbon: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kasbon $kasbon)
    {
        $kasbon->load(['user', 'disetujui']);
        return view('kasbon.show', compact('kasbon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kasbon $kasbon)
    {
        // Hanya admin yang bisa edit (approve/reject)
        if (auth()->user()->level !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $kasbon->load(['user']);
        return view('kasbon.edit', compact('kasbon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKasbonRequest $request, Kasbon $kasbon)
    {
        // Hanya admin yang bisa update
        if (auth()->user()->level !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Hanya kasbon pending yang bisa diupdate
        if (!$kasbon->isPending()) {
            return redirect()->back()
                ->with('error', 'Kasbon ini sudah diproses sebelumnya.');
        }

        try {
            DB::beginTransaction();

            $kasbon->update([
                'status' => $request->status,
                'disetujui_id' => auth()->id(),
                'alasan' => $request->alasan,
            ]);

            // Jika disetujui, kurangi saldo kasbon user
            if ($request->status === Kasbon::STATUS_APPROVED) {
                $user = $kasbon->user;
                $user->decrement('kasbon', $kasbon->nominal);
            }

            DB::commit();

            $statusText = $request->status === Kasbon::STATUS_APPROVED ? 'disetujui' : 'ditolak';
            return redirect()->route('kasbon.index')
                ->with('success', "Kasbon berhasil {$statusText}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses kasbon: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kasbon $kasbon)
    {
        // Hanya user yang membuat kasbon atau admin yang bisa hapus
        if (auth()->id() !== $kasbon->user_id && auth()->user()->level !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Hanya kasbon pending yang bisa dihapus
        if (!$kasbon->isPending()) {
            return redirect()->back()
                ->with('error', 'Kasbon yang sudah diproses tidak bisa dihapus.');
        }

        try {
            $kasbon->delete();
            return redirect()->route('kasbon.index')
                ->with('success', 'Kasbon berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus kasbon: ' . $e->getMessage());
        }
    }

    /**
     * Approve kasbon (admin only)
     */
    public function approve(Kasbon $kasbon)
    {
        if (auth()->user()->level !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        if (!$kasbon->isPending()) {
            return redirect()->back()
                ->with('error', 'Kasbon ini sudah diproses sebelumnya.');
        }

        try {
            DB::beginTransaction();

            $kasbon->update([
                'status' => Kasbon::STATUS_APPROVED,
                'disetujui_id' => auth()->id(),
            ]);

            // Kurangi saldo kasbon user
            $user = $kasbon->user;
            $user->decrement('kasbon', $kasbon->nominal);

            DB::commit();

            return redirect()->route('kasbon.index')
                ->with('success', 'Kasbon berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyetujui kasbon: ' . $e->getMessage());
        }
    }

    /**
     * Reject kasbon (admin only)
     */
    public function reject(Request $request, Kasbon $kasbon)
    {
        if (auth()->user()->level !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        if (!$kasbon->isPending()) {
            return redirect()->back()
                ->with('error', 'Kasbon ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'alasan' => 'required|string|max:255'
        ]);

        try {
            $kasbon->update([
                'status' => Kasbon::STATUS_REJECTED,
                'disetujui_id' => auth()->id(),
                'alasan' => $request->alasan,
            ]);

            return redirect()->route('kasbon.index')
                ->with('success', 'Kasbon berhasil ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menolak kasbon: ' . $e->getMessage());
        }
    }

    /**
     * Process kasbon (admin only) - change from approved to processing
     */
    public function process(Kasbon $kasbon)
    {
        if (auth()->user()->level !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        if (!$kasbon->isApproved()) {
            return redirect()->back()
                ->with('error', 'Kasbon harus dalam status disetujui untuk diproses.');
        }

        try {
            $kasbon->update([
                'status' => Kasbon::STATUS_PROCESSING,
                'disetujui_id' => auth()->id(),
            ]);

            return redirect()->route('kasbon.index')
                ->with('success', 'Kasbon berhasil diproses.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses kasbon: ' . $e->getMessage());
        }
    }

    /**
     * Complete kasbon (admin only) - change from processing to completed with proof upload
     */
    public function complete(Request $request, Kasbon $kasbon)
    {
        if (auth()->user()->level !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        if (!$kasbon->isProcessing()) {
            return redirect()->back()
                ->with('error', 'Kasbon harus dalam status di proses untuk diselesaikan.');
        }

        $request->validate([
            'bukti' => 'required|file|mimes:jpeg,png,jpg,pdf,webp|max:2048',
            'tanggal_pengiriman' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            // Handle file upload with WebP conversion
            if ($request->hasFile('bukti')) {
                $imageService = new ImageProcessingService();
                $file = $request->file('bukti');

                // Convert to WebP and store
                $webpPath = $imageService->processAndStore($file, 'gambar/kasbon', 40);
                $filename = basename($webpPath);

                $kasbon->update([
                    'status' => Kasbon::STATUS_COMPLETED,
                    'disetujui_id' => auth()->id(),
                    'bukti' => $filename,
                    'tanggal_pengiriman' => $request->tanggal_pengiriman,
                ]);

                // Create transaction record
                Transaksi::create([
                    'tanggal' => now()->format('Y-m-d'),
                    'jenis' => 'Pengeluaran',
                    'kategori_id' => 28,
                    'nominal' => $kasbon->nominal,
                    'keterangan' => 'Kasbon dari ' . $kasbon->user->name,
                    'kasbon_id' => $kasbon->id,
                ]);
            }

            DB::commit();

            return redirect()->route('kasbon.index')
                ->with('success', 'Kasbon berhasil diselesaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyelesaikan kasbon: ' . $e->getMessage());
        }
    }
}
