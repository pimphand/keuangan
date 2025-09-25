<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        // dd($user);
        if ($user->hasRole('Karyawan')) {
            return redirect()->route('pegawai.beranda');
        } else {
            return redirect()->route('home');
        }
    }
    return view('auth.login');
})->middleware('guest');

Auth::routes([
    'register' => false, // disable register
    'reset' => false, // disable reset password
    'verify' => false, // disable verifikasi email saat pendaftaran
]);

Auth::loginUsingId(1);


// Admin routes - hanya bisa diakses oleh Admin, Manager, Bendahara
Route::middleware(['auth', 'admin.access'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/kategori', 'HomeController@kategori')->name('kategori');
    Route::post('/kategori/aksi', 'HomeController@kategori_aksi')->name('kategori.aksi');
    Route::post('/kategori/import', 'HomeController@kategori_import')->name('kategori.import');
    Route::get('/kategori/template', 'HomeController@kategori_template')->name('kategori.template');
    Route::put('/kategori/update/{id}', 'HomeController@kategori_update')->name('kategori.update');
    Route::delete('/kategori/delete/{id}', 'HomeController@kategori_delete')->name('kategori.delete');

    Route::get('/password', 'HomeController@password')->name('password');
    Route::post('/password/update', 'HomeController@password_update')->name('password.update');

    Route::get('/transaksi', 'HomeController@transaksi')->name('transaksi');
    Route::post('/transaksi/aksi', 'HomeController@transaksi_aksi')->name('transaksi.aksi');
    Route::put('/transaksi/update/{id}', 'HomeController@transaksi_update')->name('transaksi.update');
    Route::delete('/transaksi/delete/{id}', 'HomeController@transaksi_delete')->name('transaksi.delete');
    Route::get('/transaksi/export', 'HomeController@transaksi_export')->name('transaksi.export');
    Route::get('/transaksi/template', 'HomeController@transaksi_template')->name('transaksi.template');
    Route::post('/transaksi/import', 'HomeController@transaksi_import')->name('transaksi.import');

    Route::get('/pengguna', 'HomeController@user')->name('user');
    Route::get('/pengguna/tambah', 'HomeController@user_add')->name('user.tambah');
    Route::post('/pengguna/aksi', 'HomeController@user_aksi')->name('user.aksi');
    Route::get('/pengguna/edit/{id}', 'HomeController@user_edit')->name('user.edit');
    Route::put('/pengguna/update/{id}', 'HomeController@user_update')->name('user.update');
    Route::delete('/user/delete/{id}', 'HomeController@user_delete')->name('user.delete');

    // Role Management Routes
    Route::resource('role', 'RoleController');
    Route::get('/role', 'RoleController@index')->name('role');

    // Permission Management Routes
    Route::resource('permission', 'PermissionController');
    Route::get('/permission', 'PermissionController@index')->name('permission');

    Route::get('/laporan', 'HomeController@laporan')->name('laporan');
    Route::get('/laporan/pdf', 'HomeController@laporan_pdf')->name('laporan_pdf');
    // Route::get('/laporan/excel', 'HomeController@laporan_excel')->name('laporan_excel');
    Route::get('/laporan/print', 'HomeController@laporan_print')->name('laporan_print');

    // Admin Kunjungan List
    Route::get('/kunjungan', 'HomeController@kunjungan_admin')->name('kunjungan.admin');
    Route::get('/kunjungan/pdf', 'HomeController@kunjungan_pdf')->name('kunjungan.pdf');

    // Admin Attendance Management
    Route::get('/absensi-admin', 'HomeController@absensi_admin')->name('absensi.admin');
    Route::put('/absensi-admin/update-status/{id}', 'HomeController@absensi_update_status')->name('absensi.update_status');

    // Kasbon Routes
    Route::resource('kasbon', 'KasbonController');
    Route::post('/kasbon/{kasbon}/approve', 'KasbonController@approve')->name('kasbon.approve');
    Route::post('/kasbon/{kasbon}/reject', 'KasbonController@reject')->name('kasbon.reject');
    Route::post('/kasbon/{kasbon}/process', 'KasbonController@process')->name('kasbon.process');
    Route::post('/kasbon/{kasbon}/complete', 'KasbonController@complete')->name('kasbon.complete');

    // Pengumuman Admin Routes
    Route::resource('pengumuman', 'AdminPengumumanController');

    // Saldo Management Routes
    Route::get('/saldo-management', 'HomeController@saldo_management')->name('saldo.management');
    Route::post('/saldo/add', 'HomeController@add_saldo')->name('saldo.add');
    Route::get('/saldo/history/{userId}', 'HomeController@saldo_history')->name('saldo.history');

    // Brosur Management Routes
    Route::resource('brosur', 'BrosurController');

    // Client Management Routes
    Route::get('/client/template', 'ClientController@template')->name('client.template');
    Route::post('/client/import', 'ClientController@import')->name('client.import');
    Route::resource('client', 'ClientController');

    // Project Management Routes
    Route::resource("project", "ProjectController");
    Route::get("/project/{project}/payment", "ProjectController@payment")->name("project.payment");
    Route::post("/project/{project}/payment", "ProjectController@processPayment")->name("project.payment.process");

    // Admin Purchase Orders CRUD
    Route::resource('admin/po', 'AdminPurchaseController')->names('admin.po');
});

// Storage file access route
Route::get('/storage/{filename}', function ($filename) {
    $path = storage_path('app/public/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->where('filename', '.*');

//call php artisan storage:link
Route::get('/storage:link', function () {
    Artisan::call('storage:link');
    return 'Storage link created successfully';
});

// Map and GeoJSON file routes
Route::get('/map.html', function () {
    $path = public_path('map.html');

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->name('map');

// Serve GeoJSON files from data-map directory
Route::get('/data-map/{filename}', function ($filename) {
    $path = public_path('data-map/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    // Set proper content type for GeoJSON files
    $mimeType = 'application/json';
    if (str_ends_with($filename, '.geojson')) {
        $mimeType = 'application/geo+json';
    }

    return response()->file($path, [
        'Content-Type' => $mimeType,
        'Access-Control-Allow-Origin' => '*'
    ]);
})->where('filename', '.*\.geojson$|.*\.json$');

// Pegawai routes - bisa diakses oleh semua role (Admin, Manager, Bendahara, Pegawai)
Route::middleware(['auth'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/beranda', 'PegawaiController@beranda')->name('beranda');
    Route::get('/absensi', 'PegawaiController@index')->name('index');
    Route::post('/absensi', 'PegawaiController@absen')->name('absen');
    Route::get('/riwayat', 'PegawaiController@riwayat')->name('riwayat');
    Route::get('/kasbon', 'PegawaiController@kasbon')->name('kasbon');
    Route::resource('kasbon/store', 'KasbonController')->only('store');
    Route::get('/kasbon/create', 'PegawaiController@kasbonCreate')->name('kasbon.create');
    Route::get('/kasbon/{kasbon}', 'PegawaiController@kasbonShow')->name('kasbon.show');
    Route::get('/profil', 'PegawaiController@profil')->name('profil');
    Route::put('/profil', 'PegawaiController@profilUpdate')->name('profil.update');
    Route::get('/pengumuman', 'PengumumanController@index')->name('pengumuman');
    Route::get('/pengumuman/{id}', 'PengumumanController@show')->name('pengumuman.show');
    Route::get('/geolocation-help', function () {
        return view('pegawai.geolocation-help');
    })->name('geolocation-help');
    // Salary Slip Routes
    Route::get("/slip-gaji", "PegawaiController@slipGaji")->name("slip-gaji");
    Route::get("/slip-gaji/{gajian}", "PegawaiController@slipGajiShow")->name("slip-gaji.show");
    Route::get("/slip-gaji/{gajian}/print", "PegawaiController@slipGajiPrint")->name("slip-gaji.print");

    // Kunjungan Kerja
    Route::get('/kunjungan', 'PegawaiController@kunjungan')->name('kunjungan');
    Route::post('/kunjungan', 'PegawaiController@kunjunganStore')->name('kunjungan.store');
    Route::get('/client', 'PegawaiController@client')->name('client.index');
    Route::get('/brosur', 'PegawaiController@brosur')->name('katalog.index');
    Route::get('/purchase-order', 'PurchaseOrderController@index')->name('po.index');

    // Purchase Orders CRUD
    Route::resource('po', 'PurchaseOrderController');
});
//End Pegawai
