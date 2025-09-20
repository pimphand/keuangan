<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKasbonRequest;
use App\Http\Requests\UpdateKasbonRequest;
use App\Models\Kasbon;
use App\Models\User;
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
            DB::transaction(function () use ($request) {
                $kasbon = Kasbon::create([
                    'user_id' => auth()->id(),
                    'nominal' => $request->nominal,
                    'keterangan' => $request->keterangan,
                    'status' => Kasbon::STATUS_PENDING,
                ]);

                Auth::user()->decrement('kasbon', $request->nominal);
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
}
