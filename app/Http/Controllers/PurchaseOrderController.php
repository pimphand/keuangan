<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Services\ImageProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $purchaseOrders = PurchaseOrder::withCount('items')
            ->where('user_id', Auth::id())
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q')->trim();
                $query->where(function ($q) use ($term) {
                    $q->where('po_number', 'like', "%{$term}%")
                        ->orWhere('client_name', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = $request->string('status');
                $query->whereIn('status', ['pending', 'approved', 'rejected'])
                    ->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('pegawai.purchase_order.index', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pegawai.purchase_order.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_type' => 'required|in:Pemerintahan,Swasta',
            'client_name' => 'required|string|max:255',
            'client_address' => 'required|string',
            'client_phone_number' => ['required', 'regex:/^[0-9]+$/', 'max:20'],
            'client_nik' => 'required|digits:16',
            'client_ktp_name' => 'required|string|max:255',
            'ktp_photo' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'nullable|integer|min:0',
        ]);


        try {
            $purchaseOrder = DB::transaction(function () use ($validated) {
                $po = PurchaseOrder::create([
                    'client_type' => $validated['client_type'],
                    'client_name' => $validated['client_name'],
                    'client_address' => $validated['client_address'],
                    'client_phone_number' => $validated['client_phone_number'],
                    'client_nik' => $validated['client_nik'],
                    'client_ktp_name' => $validated['client_ktp_name'],
                    'ktp_photo' => $validated['ktp_photo'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($validated['items'] as $item) {
                    PurchaseOrderItem::create([
                        'purchase_order_id' => $po->id,
                        'product_name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'] ?? 0,
                    ]);
                }

                return $po;
            });

            return redirect()
                ->route('pegawai.po.show', $purchaseOrder->id)
                ->with('success', 'Purchase Order berhasil dibuat');
        } catch (\Throwable $th) {
            \Log::error('PO Store Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Purchase Order gagal dibuat');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchaseOrder = PurchaseOrder::with('items')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pegawai.purchase_order.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $purchaseOrder = PurchaseOrder::with('items')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pegawai.purchase_order.edit', compact('purchaseOrder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ImageProcessingService $imageService)
    {
        $purchaseOrder = PurchaseOrder::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'client_type' => 'required|in:Pemerintahan,Swasta',
            'client_name' => 'required|string|max:255',
            'client_address' => 'required|string',
            'client_phone_number' => 'required|string|max:30',
            'client_nik' => 'required|string|max:20',
            'client_ktp_name' => 'required|string|max:255',
            'ktp_photo' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:pending,approved,rejected',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'nullable|integer|min:0',
        ]);


        DB::transaction(function () use ($purchaseOrder, $validated) {
            $purchaseOrder->update([
                'client_type' => $validated['client_type'],
                'client_name' => $validated['client_name'],
                'client_address' => $validated['client_address'],
                'client_phone_number' => $validated['client_phone_number'],
                'client_nik' => $validated['client_nik'],
                'client_ktp_name' => $validated['client_ktp_name'],
                'ktp_photo' => $validated['ktp_photo'],
                'notes' => $validated['notes'] ?? null,
                'status' => $validated['status'] ?? $purchaseOrder->status,
            ]);

            $purchaseOrder->items()->delete();
            foreach ($validated['items'] as $item) {
                $purchaseOrder->items()->create([
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'] ?? 0,
                ]);
            }
        });

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Purchase Order updated successfully',
                'data' => $purchaseOrder->load('items'),
            ]);
        }

        return redirect()->route('pegawai.po.show', $purchaseOrder->id)
            ->with('success', 'Purchase Order berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchaseOrder = PurchaseOrder::where('user_id', Auth::id())->findOrFail($id);
        $purchaseOrder->delete();

        return redirect()->route('pegawai.po.index')->with('success', 'Purchase Order berhasil dihapus');
    }
}
