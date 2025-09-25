<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Brosur;
use App\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with(['client', 'brosur'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.project.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clientId = $request->get('client_id');
        $clients = Client::orderBy('nama')->get();
        $brosurs = Brosur::orderBy('nama')->get();

        return view('admin.project.create', compact('clients', 'brosurs', 'clientId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'brosur_id' => 'required|exists:brosurs,id',
            'status' => 'required|in:belum bayar,bayar,kurang',
            'harga' => 'required|integer|min:0',
            'total_bayar' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['sisa_bayar'] = $data['harga'] - $data['total_bayar'];

        Project::create($data);

        return redirect()->route('client.show', $request->client_id)
            ->with('success', 'Project berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['client', 'brosur']);
        return view('admin.project.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $clients = Client::orderBy('nama')->get();
        $brosurs = Brosur::orderBy('nama')->get();

        return view('admin.project.edit', compact('project', 'clients', 'brosurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'brosur_id' => 'required|exists:brosurs,id',
            'status' => 'required|in:belum bayar,bayar,kurang',
            'harga' => 'required|integer|min:0',
            'total_bayar' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['sisa_bayar'] = $data['harga'] - $data['total_bayar'];

        $project->update($data);

        return redirect()->route('project.index')
            ->with('success', 'Project berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('project.index')
            ->with('success', 'Project berhasil dihapus.');
    }


    /**
     * Show the payment form for the specified project.
     */
    public function payment(Project $project)
    {
        $project->load(['client', 'brosur', 'paymentHistories']);
        return view('admin.project.payment', compact('project'));
    }

    /**
     * Process payment for the specified project.
     */
    public function processPayment(Request $request, Project $project)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            return DB::transaction(function () use ($request, $project) {
                // Create payment history record
                $project->paymentHistories()->create([
                    'amount' => $request->amount,
                    'payment_date' => $request->payment_date,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                ]);

                // Update project payment totals
                $newTotalBayar = $project->total_bayar + $request->amount;
                $newSisaBayar = $project->harga - $newTotalBayar;

                // Update status based on payment
                $newStatus = 'belum bayar';
                if ($newSisaBayar <= 0) {
                    $newStatus = 'bayar';
                } elseif ($newTotalBayar > 0) {
                    $newStatus = 'kurang';
                }

                $project->update([
                    'total_bayar' => $newTotalBayar,
                    'sisa_bayar' => $newSisaBayar,
                    'status' => $newStatus,
                ]);

                Transaksi::create([
                    'tanggal' => now()->format('Y-m-d'),
                    'jenis' => 'Pemasukan',
                    'kategori_id' => 35,
                    'nominal' => $request->amount,
                    'keterangan' => "Pembayaran Project  $project->nama Client " . $project->client->nama . ' sebesar Rp ' . number_format($request->amount, 2, ',', '.'),
                ]);

                return redirect()->route('project.payment', $project)
                    ->with('success', 'Pembayaran berhasil diproses.');
            });
        } catch (\Throwable $th) {
            return redirect()->route('project.payment', $project)
                ->with('error', 'Pembayaran gagal diproses.');
        }
    }
}
