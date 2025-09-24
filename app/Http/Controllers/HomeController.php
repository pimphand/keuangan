<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Kategori;
use App\Transaksi;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Role;
use App\Models\SaldoHistory;
use App\Services\ImageProcessingService;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanExport;
use App\Exports\KategoriTemplateExport;
use App\Exports\TransaksiExport;
use App\Exports\TransaksiTemplateExport;
use App\Imports\KategoriImport;
use App\Imports\TransaksiImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\Gajian;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\KunjunganKerja;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Redirect pegawai to beranda
        if (auth()->user()->roles()->first()->name == 'Karyawan') {
            return redirect()->route('pegawai.beranda');
        }

        $kategori = Kategori::all();
        $transaksi = Transaksi::all();
        $tanggal = date('Y-m-d');
        $bulan = date('m');
        $tahun = date('Y');

        $pemasukan_hari_ini = DB::table('transaksi')
            ->select(DB::raw('SUM(nominal) as total'))
            ->where('jenis', 'Pemasukan')
            ->whereDate('tanggal', $tanggal)
            ->first();

        $pemasukan_bulan_ini = DB::table('transaksi')
            ->select(DB::raw('SUM(nominal) as total'))
            ->where('jenis', 'Pemasukan')
            ->whereMonth('tanggal', $bulan)
            ->first();

        $pemasukan_tahun_ini = DB::table('transaksi')
            ->select(DB::raw('SUM(nominal) as total'))
            ->where('jenis', 'Pemasukan')
            ->whereYear('tanggal', $tahun)
            ->first();

        $seluruh_pemasukan = DB::table('transaksi')
            ->select(DB::raw('SUM(nominal) as total'))
            ->where('jenis', 'Pemasukan')
            ->first();

        $pengeluaran_hari_ini = DB::table('transaksi')
            ->select(DB::raw('SUM(nominal) as total'))
            ->where('jenis', 'Pengeluaran')
            ->whereDate('tanggal', $tanggal)
            ->first();

        $pengeluaran_bulan_ini = DB::table('transaksi')
            ->select(DB::raw('SUM(nominal) as total'))
            ->where('jenis', 'Pengeluaran')
            ->whereMonth('tanggal', $bulan)
            ->first();

        $pengeluaran_tahun_ini = DB::table('transaksi')
            ->select(DB::raw('SUM(nominal) as total'))
            ->where('jenis', 'Pengeluaran')
            ->whereYear('tanggal', $tahun)
            ->first();

        $seluruh_pengeluaran = DB::table('transaksi')
            ->select(DB::raw('SUM(nominal) as total'))
            ->where('jenis', 'Pengeluaran')
            ->first();

        return view(
            'app.index',
            [
                'pemasukan_hari_ini' => $pemasukan_hari_ini,
                'pemasukan_bulan_ini' => $pemasukan_bulan_ini,
                'pemasukan_tahun_ini' => $pemasukan_tahun_ini,
                'seluruh_pemasukan' => $seluruh_pemasukan,
                'pengeluaran_hari_ini' => $pengeluaran_hari_ini,
                'pengeluaran_bulan_ini' => $pengeluaran_bulan_ini,
                'pengeluaran_tahun_ini' => $pengeluaran_tahun_ini,
                'seluruh_pengeluaran' => $seluruh_pengeluaran,
                'kategori' => $kategori,
                'transaksi' => $transaksi,
            ]
        );
    }

    public function kategori()
    {
        $kategori = Kategori::orderBy('kategori', 'asc')->get();
        return view('app.kategori', ['kategori' => $kategori]);
    }

    public function kategori_aksi(Request $req)
    {
        $nama = $req->input('nama');
        Kategori::create(['kategori' => $nama]);
        return redirect('kategori')->with('success', 'Kategori telah disimpan');
    }

    public function kategori_update($id, Request $req)
    {
        $nama = $req->input('nama');
        $kategori = Kategori::find($id);
        $kategori->kategori = $nama;
        $kategori->save();
        return redirect('kategori')->with('success', 'Kategori telah diupdate');
    }

    public function kategori_delete($id)
    {
        $kategori = Kategori::find($id);
        $kategori->delete();

        $tt = Transaksi::where('kategori_id', $id)->get();

        if ($tt->count() > 0) {
            $transaksi = Transaksi::where('kategori_id', $id)->first();
            $transaksi->kategori_id = "1";
            $transaksi->save();
        }
        return redirect('kategori')->with('success', 'Kategori telah dihapus');
    }

    public function kategori_import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new KategoriImport, $request->file('file'));
            return redirect('kategori')->with('success', 'Data kategori berhasil diimport!');
        } catch (\Exception $e) {
            return redirect('kategori')->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }

    public function kategori_template()
    {
        return Excel::download(new KategoriTemplateExport, 'template_kategori.xlsx');
    }

    public function password()
    {
        return view('app.password');
    }

    public function password_update(Request $request)
    {

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        }

        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success", "Password telah diganti!");
    }


    public function transaksi()
    {
        $kategori = Kategori::orderBy('kategori', 'asc')->get();
        $transaksi = Transaksi::orderBy('tanggal', 'desc')->get();
        return view('app.transaksi', ['transaksi' => $transaksi, 'kategori' => $kategori]);
    }

    public function transaksi_aksi(Request $req)
    {
        $tanggal = $req->input('tanggal');
        $jenis = $req->input('jenis');
        $kategori = $req->input('kategori');
        $nominal = $req->input('nominal');
        $keterangan = $req->input('keterangan');

        Transaksi::create([
            'tanggal' => $tanggal,
            'jenis' => $jenis,
            'kategori_id' => $kategori,
            'nominal' => $nominal,
            'keterangan' => $keterangan,
        ]);

        return redirect()->back()->with("success", "Transaksi telah disimpan!");
    }


    public function transaksi_update($id, Request $req)
    {
        $tanggal = $req->input('tanggal');
        $jenis = $req->input('jenis');
        $kategori = $req->input('kategori');
        $nominal = $req->input('nominal');
        $keterangan = $req->input('keterangan');

        $transaksi = Transaksi::find($id);
        $transaksi->tanggal = $tanggal;
        $transaksi->jenis = $jenis;
        $transaksi->kategori_id = $kategori;
        $transaksi->nominal = $nominal;
        $transaksi->keterangan = $keterangan;
        $transaksi->save();

        return redirect()->back()->with("success", "Transaksi telah diupdate!");
    }

    public function transaksi_delete($id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->delete();
        return redirect()->back()->with("success", "Transaksi telah dihapus!");
    }

    public function transaksi_export()
    {
        $transaksi = Transaksi::with('kategori')->orderBy('id', 'desc')->get();
        return Excel::download(new TransaksiExport($transaksi), 'data_transaksi.xlsx');
    }

    public function transaksi_template()
    {
        return Excel::download(new TransaksiTemplateExport, 'template_transaksi.xlsx');
    }

    public function transaksi_import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new TransaksiImport, $request->file('file'));
            return redirect('transaksi')->with('success', 'Data transaksi berhasil diimport!');
        } catch (\Exception $e) {
            return redirect('transaksi')->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }

    public function laporan()
    {
        $kategori = Kategori::orderBy('kategori', 'asc')->get();

        // Check if form was submitted
        if (isset($_GET['kategori']) || isset($_GET['dari']) || isset($_GET['sampai'])) {
            $query = Transaksi::query();

            // Apply date filter if provided
            if (isset($_GET['dari']) && isset($_GET['sampai']) && !empty($_GET['dari']) && !empty($_GET['sampai'])) {
                $query->whereDate('tanggal', '>=', $_GET['dari'])
                    ->whereDate('tanggal', '<=', $_GET['sampai']);
            }

            // Apply category filter if provided
            if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
                $query->where('kategori_id', $_GET['kategori']);
            }

            $transaksi = $query->orderBy('tanggal', 'desc')->get();
        } else {
            // If no filters applied, show empty array
            $transaksi = collect();
        }

        return view('app.laporan', ['transaksi' => $transaksi, 'kategori' => $kategori]);
    }

    public function laporan_print()
    {
        $kategori = Kategori::orderBy('kategori', 'asc')->get();
        $query = Transaksi::query();

        // Apply date filter if provided
        if (isset($_GET['dari']) && isset($_GET['sampai']) && !empty($_GET['dari']) && !empty($_GET['sampai'])) {
            $query->whereDate('tanggal', '>=', $_GET['dari'])
                ->whereDate('tanggal', '<=', $_GET['sampai']);
        }

        // Apply category filter if provided
        if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
            $query->where('kategori_id', $_GET['kategori']);
        }

        $transaksi = $query->orderBy('tanggal', 'desc')->get();
        return view('app.laporan_print', ['transaksi' => $transaksi, 'kategori' => $kategori]);
    }

    // public function laporan_excel()
    // {
    //     return Excel::download(new LaporanExport, 'Laporan.xlsx');
    // }

    public function laporan_pdf()
    {
        $kategori = Kategori::orderBy('kategori', 'asc')->get();
        $query = Transaksi::query();

        // Apply date filter if provided
        if (isset($_GET['dari']) && isset($_GET['sampai']) && !empty($_GET['dari']) && !empty($_GET['sampai'])) {
            $query->whereDate('tanggal', '>=', $_GET['dari'])
                ->whereDate('tanggal', '<=', $_GET['sampai']);
        }

        // Apply category filter if provided
        if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
            $query->where('kategori_id', $_GET['kategori']);
        }

        $transaksi = $query->orderBy('tanggal', 'asc')->get();
        $pdf = Pdf::loadView('app.laporan_pdf', ['transaksi' => $transaksi, 'kategori' => $kategori]);
        return $pdf->download('Laporan Keuangan.pdf');
    }


    public function user()
    {
        $user = User::with('roles')->get();
        return view('app.user', ['user' => $user]);
    }

    public function user_add()
    {
        $roles = Role::active()->get();
        return view('app.user_tambah', compact('roles'));
    }

    public function user_aksi(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048',
            'saldo' => 'nullable|numeric|min:0',
            'kasbon' => 'nullable|numeric|min:0',
        ]);

        // menyimpan data file yang diupload ke variabel $file
        $file = $request->file('foto');

        // cek jika gambar kosong
        if ($file != "") {
            // Process image with WebP conversion and compression
            $imageService = new ImageProcessingService();
            $nama_file = $imageService->processAndStore($file, 'gambar/user', 40); // 40% quality
        } else {
            $nama_file = "";
        }

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'level' => "admin",
            'foto' => $nama_file ?? '',
            'saldo' => $request->saldo ?? 0,
            'kasbon' => $request->kasbon ?? 0,
            'tunjangan' => $request->tunjangan ?? 0,
        ]);

        // Assign roles to user
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect(route('user'))->with('success', 'User telah disimpan');
    }

    public function user_edit($id)
    {
        $user = User::with('roles')->find($id);
        $roles = Role::active()->get();
        return view('app.user_edit', ['user' => $user, 'roles' => $roles]);
    }

    public function user_update($id, Request $req)
    {
        $this->validate($req, [
            'nama' => 'required',
            'email' => 'required|email',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048',
            'saldo' => 'nullable|numeric|min:0',
            'kasbon' => 'nullable|numeric|min:0',
        ]);

        $name = $req->input('nama');
        $email = $req->input('email');
        $password = $req->input('password');

        $user = User::find($id);
        $user->name = $name;
        $user->email = $email;
        if ($password != "") {
            $user->password = bcrypt($password);
        }

        // Update saldo and kasbon
        $user->saldo = $req->saldo ?? $user->saldo;
        $user->kasbon = $req->kasbon ?? $user->kasbon;
        $user->tunjangan = $req->tunjangan ?? $user->tunjangan;

        // menyimpan data file yang diupload ke variabel $file
        $file = $req->file('foto');

        // cek jika gambar tidak kosong
        if ($file != "") {
            // Process image with WebP conversion and compression
            $imageService = new ImageProcessingService();
            $nama_file = $imageService->processAndStore($file, 'gambar/user', 40); // 40% quality

            // hapus file gambar lama
            if ($user->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }

            $user->foto = $nama_file;
        }
        $user->save();

        // Update user roles
        if ($req->has('roles')) {
            $user->syncRoles($req->roles);
        } else {
            $user->roles()->detach();
        }

        return redirect(route('user'))->with("success", "User telah diupdate!");
    }

    public function user_delete($id)
    {
        $user = User::find($id);
        // hapus file gambar lama
        File::delete('gambar/user/' . $user->foto);
        $user->delete();

        return redirect(route('user'))->with("success", "User telah dihapus!");
    }

    public function absensi_admin()
    {
        $absensi = Absensi::with('user')
            ->orderBy('waktu_absen', 'desc')
            ->paginate(20);

        return view('app.absensi_admin', compact('absensi'));
    }

    public function absensi_update_status(Request $request, $id)
    {
        $absensi = Absensi::find($id);
        $absensi->status = $request->status;
        $absensi->save();

        return redirect()->back()->with('success', 'Status absensi berhasil diupdate!');
    }

    /**
     * Display saldo management page
     */
    public function saldo_management()
    {
        $users = User::with('roles')->get();
        $currentMonth = date('Y-m');
        $isDateAllowed = $this->isDateAllowed();

        return view('app.saldo_management', compact('users', 'currentMonth', 'isDateAllowed'));
    }

    /**
     * Add saldo to user
     */
    public function add_saldo(Request $request)
    {
        // Check if current date is within allowed range
        if (!$this->isDateAllowed()) {
            return redirect()->back()->with('error', 'Penambahan saldo hanya dapat dilakukan pada tanggal ' . env('SALDO_ALLOWED_START_DATE', '1') . '-' . env('SALDO_ALLOWED_END_DATE', '10') . ' setiap bulannya.');
        }

        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:255',
            'tunjangan' => 'nullable|numeric|min:0',
        ]);

        $user = User::find($request->user_id);

        // Check if user already received saldo this month
        if (!$user->canReceiveSaldoThisMonth()) {
            return redirect()->back()->with('error', 'User ' . $user->name . ' sudah menerima penambahan saldo untuk bulan ini.');
        }

        // Calculate Gaji Akhir automatically: saldo - kasbon_terpakai
        $tunjangan = $request->tunjangan ?? 0;
        $gajiAkhir = ($user->saldo + $tunjangan) - $user->kasbon_terpakai;

        // Ensure gaji akhir is not negative
        $gajiAkhir = max(0, $gajiAkhir);

        // Validate that the calculated amount matches the frontend calculation
        if (abs($gajiAkhir - $request->amount) > 0.01) {
            return redirect()->back()->with('error', 'Terjadi kesalahan dalam perhitungan Gaji Akhir. Silakan refresh halaman dan coba lagi.');
        }

        try {
            DB::beginTransaction();

            // Record in saldo history
            $saldo = SaldoHistory::create([
                'user_id' => $user->id,
                'amount' => $gajiAkhir,
                'month_year' => date('Y-m'),
                'notes' => $request->notes ?: 'Gaji Akhir (Otomatis: Saldo - Kasbon Terpakai)',
                'admin_id' => Auth::id()
            ]);

            // Create transaction record
            Transaksi::create([
                'tanggal' => now()->format('Y-m-d'),
                'jenis' => 'Pengeluaran',
                'kategori_id' => 7,
                'nominal' => $gajiAkhir,
                'keterangan' => 'Gaji Akhir ke ' . $user->name . ' sebesar Rp ' . number_format($gajiAkhir, 2, ',', '.'),
                'saldo_history_id' => $saldo->id,
            ]);

            Gajian::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'jabatan' => $user->roles()->first()->name ?? 'Karyawan',
                'gaji_pokok' => $user->saldo,           // User's base salary
                'tunjangan' => $request->tunjangan ?? 0, // Allowance from request
                'potongan' => $user->kasbon_terpakai,    // Deductions (used kasbon)
                'gaji_bersih' => $gajiAkhir,            // Final salary after calculations
                'periode_gaji' => now()->format('Y-m-d'), // Payroll period
                'tanggal_pembayaran' => now()->format('Y-m-d'), // Payment date
                'status' => 'Dibayar',                   // Payment status
            ]);
            // Add saldo to user (using calculated amount)
            $user->kasbon_terpakai = 0;
            $user->save();
            DB::commit();

            return redirect()->back()->with('success', 'Gaji Akhir berhasil ditambahkan ke ' . $user->name . ' sebesar Rp ' . number_format($gajiAkhir, 2, ',', '.'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get saldo history for a user
     */
    public function saldo_history($userId)
    {
        $user = User::findOrFail($userId);
        $history = $user->saldoHistory()->with('admin')->orderBy('created_at', 'desc')->get();

        return view('app.saldo_history', compact('user', 'history'));
    }

    /**
     * Check if current date is within allowed range for saldo addition
     */
    private function isDateAllowed()
    {
        $currentDay = (int) date('d');
        $allowedStart = (int) env('SALDO_ALLOWED_START_DATE', 1);
        $allowedEnd = (int) env('SALDO_ALLOWED_END_DATE', 10);

        return $currentDay >= $allowedStart && $currentDay <= $allowedEnd;
    }

    public function kunjungan_admin(Request $request)
    {
        $query = KunjunganKerja::query()->with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_kunjungan', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_kunjungan', '<=', $request->date_to);
        }
        if ($request->filled('client')) {
            $query->where('client', 'like', '%' . $request->client . '%');
        }

        $kunjungans = $query->orderBy('tanggal_kunjungan', 'desc')->paginate(15);
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('app.kunjungan_admin', compact('kunjungans', 'users'));
    }
}
