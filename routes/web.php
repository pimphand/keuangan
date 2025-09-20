<?php

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
    // return view('welcome');
    return view('auth.login');
})->middleware('guest');

Auth::routes([
    'register' => false, // disable register
    'reset' => false, // disable reset password
    'verify' => false, // disable verifikasi email saat pendaftaran
]);

Auth::loginUsingId(1);


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


Route::get('/laporan', 'HomeController@laporan')->name('laporan');
Route::get('/laporan/pdf', 'HomeController@laporan_pdf')->name('laporan_pdf');
// Route::get('/laporan/excel', 'HomeController@laporan_excel')->name('laporan_excel');
Route::get('/laporan/print', 'HomeController@laporan_print')->name('laporan_print');

// Employee Attendance Routes
Route::prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/beranda', 'PegawaiController@beranda')->name('beranda');
    Route::get('/absensi', 'PegawaiController@index')->name('index');
    Route::post('/absensi', 'PegawaiController@absen')->name('absen');
    Route::get('/riwayat', 'PegawaiController@riwayat')->name('riwayat');
    Route::get('/kasbon', 'PegawaiController@kasbon')->name('kasbon');
    Route::get('/kasbon/create', 'PegawaiController@kasbonCreate')->name('kasbon.create');
    Route::get('/kasbon/{kasbon}', 'PegawaiController@kasbonShow')->name('kasbon.show');
    Route::get('/profil', 'PegawaiController@profil')->name('profil');
    Route::put('/profil', 'PegawaiController@profilUpdate')->name('profil.update');
    Route::get('/pengumuman', 'PengumumanController@index')->name('pengumuman');
    Route::get('/pengumuman/{id}', 'PengumumanController@show')->name('pengumuman.show');
    Route::get('/geolocation-help', function () {
        return view('pegawai.geolocation-help');
    })->name('geolocation-help');
});

// Admin Attendance Management
Route::get('/absensi-admin', 'HomeController@absensi_admin')->name('absensi.admin');
Route::put('/absensi-admin/update-status/{id}', 'HomeController@absensi_update_status')->name('absensi.update_status');

// Kasbon Routes
Route::resource('kasbon', 'KasbonController');
Route::post('/kasbon/{kasbon}/approve', 'KasbonController@approve')->name('kasbon.approve');
Route::post('/kasbon/{kasbon}/reject', 'KasbonController@reject')->name('kasbon.reject');

// Pengumuman Admin Routes
Route::prefix('admin')->name('pengumuman.admin.')->group(function () {
    Route::get('/pengumuman', 'PengumumanController@adminIndex')->name('index');
    Route::get('/pengumuman/create', 'PengumumanController@create')->name('create');
    Route::post('/pengumuman', 'PengumumanController@store')->name('store');
    Route::get('/pengumuman/{id}/edit', 'PengumumanController@edit')->name('edit');
    Route::put('/pengumuman/{id}', 'PengumumanController@update')->name('update');
    Route::delete('/pengumuman/{id}', 'PengumumanController@destroy')->name('destroy');
});
