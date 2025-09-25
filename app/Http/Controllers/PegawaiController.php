<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Absensi;
use App\Models\Client;
use App\Models\Kasbon;
use App\Models\Pengumuman;
use App\Models\Gajian;
use Carbon\Carbon;
use App\Models\KunjunganKerja;

class PegawaiController extends Controller
{
    public function beranda()
    {
        $user = Auth::user();

        // Get recent transactions for the user
        $transaksiTerkini =  $query = Kasbon::where('user_id', $user->id)->orderBy('created_at', 'desc')->limit(3)->get();

        // Get recent announcements for the user
        $pengumumanTerkini = Pengumuman::current()
            ->with(['creator'])
            ->orderByRaw("CASE prioritas WHEN 'tinggi' THEN 1 WHEN 'sedang' THEN 2 WHEN 'rendah' THEN 3 ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Get last 3 kunjungan for this user
        $kunjunganTerakhir = KunjunganKerja::where('user_id', $user->id)
            ->orderBy('tanggal_kunjungan', 'desc')
            ->limit(3)
            ->get();

        return view('pegawai.beranda', compact('user', 'transaksiTerkini', 'pengumumanTerkini', 'kunjunganTerakhir'));
    }

    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Get today's attendance records
        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('waktu_absen', $today)
            ->orderBy('waktu_absen', 'desc')
            ->get();

        // Check if user has already done specific attendance types today
        $sudahMasuk = $absensiHariIni->where('jenis', 'masuk')->isNotEmpty();
        $sudahKeluar = $absensiHariIni->where('jenis', 'keluar')->isNotEmpty();
        $sudahIzin = $absensiHariIni->where('jenis', 'izin')->isNotEmpty();
        $sudahSakit = $absensiHariIni->where('jenis', 'sakit')->isNotEmpty();

        return view('pegawai.absensi', compact(
            'absensiHariIni',
            'sudahMasuk',
            'sudahKeluar',
            'sudahIzin',
            'sudahSakit'
        ));
    }

    public function absen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required|in:masuk,keluar,izin,sakit',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'keterangan' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate location radius
        if (!$this->validateLocationRadius($request->latitude, $request->longitude)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar radius kantor. Silakan mendekat ke kantor untuk melakukan absensi.'
            ], 400);
        }

        $user = Auth::user();
        $jenis = $request->jenis;
        $today = Carbon::today();

        // Check if user has already done this type of attendance today
        $sudahAbsen = Absensi::where('user_id', $user->id)
            ->where('jenis', $jenis)
            ->whereDate('waktu_absen', $today)
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi ' . $jenis . ' hari ini'
            ], 400);
        }

        // Validate attendance sequence
        if (!$this->validateAttendanceSequence($user->id, $jenis, $today)) {
            return response()->json([
                'success' => false,
                'message' => 'Urutan absensi tidak valid'
            ], 400);
        }

        try {
            // Handle photo upload - convert to WebP and store
            $foto = $request->file('foto');
            $fotoName = time() . '_' . pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';

            // Create image resource based on file type
            $imageType = exif_imagetype($foto->getPathname());
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($foto->getPathname());
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($foto->getPathname());
                    break;
                case IMAGETYPE_WEBP:
                    $image = imagecreatefromwebp($foto->getPathname());
                    break;
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Format gambar tidak didukung'
                    ], 400);
            }

            // Convert to WebP and save
            $fotoPath = 'gambar/absensi/' . $fotoName;
            $fullPath = public_path($fotoPath);

            // Ensure directory exists
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            imagewebp($image, $fullPath, 80); // 80% quality
            imagedestroy($image);

            // Get address from coordinates (you can integrate with Google Maps API)
            $alamat = $this->getAddressFromCoordinates($request->latitude, $request->longitude);

            // Check work hours restrictions
            $waktuAbsen = now();
            $terlambat = false;

            // Check if late arrival (after configured time) for masuk attendance
            if ($jenis === 'masuk') {
                $workStartTime = config('app.work_hours.start_time');
                $lateThresholdMinutes = (int) config('app.work_hours.late_threshold_minutes');
                $jamMasukKerja = Carbon::today()->setTimeFromTimeString($workStartTime)->addMinutes($lateThresholdMinutes);
                $terlambat = $waktuAbsen->gt($jamMasukKerja);
            }

            // Check if trying to checkout before work end time
            if ($jenis === 'keluar') {
                $workEndTime = config('app.work_hours.end_time');
                $jamPulangKerja = Carbon::today()->setTimeFromTimeString($workEndTime);

                if ($waktuAbsen->lt($jamPulangKerja)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Belum waktunya pulang! Jam pulang kerja adalah ' . $workEndTime . '. Silakan tunggu hingga jam ' . $workEndTime . ' untuk melakukan absen pulang.'
                    ], 400);
                }
            }

            // Create attendance record
            $absensi = Absensi::create([
                'user_id' => $user->id,
                'jenis' => $jenis,
                'waktu_absen' => $waktuAbsen,
                'foto' => $fotoPath,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'alamat' => $alamat,
                'keterangan' => $request->keterangan,
                'status' => 'pending',
                'terlambat' => $terlambat
            ]);

            $message = 'Absensi ' . $jenis . ' berhasil';
            if ($terlambat) {
                $message .= ' (Terlambat)';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $absensi,
                'terlambat' => $terlambat
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function validateAttendanceSequence($userId, $jenis, $today)
    {
        $absensiHariIni = Absensi::where('user_id', $userId)
            ->whereDate('waktu_absen', $today)
            ->pluck('jenis')
            ->toArray();

        switch ($jenis) {
            case 'masuk':
                // Can only check in if haven't checked in yet and haven't taken leave/sick
                return !in_array('masuk', $absensiHariIni) &&
                    !in_array('izin', $absensiHariIni) &&
                    !in_array('sakit', $absensiHariIni);

            case 'keluar':
                // Can only check out if already checked in and haven't checked out yet
                return in_array('masuk', $absensiHariIni) &&
                    !in_array('keluar', $absensiHariIni);

            case 'izin':
                // Can only take leave if haven't checked in yet and haven't taken leave/sick
                return !in_array('masuk', $absensiHariIni) &&
                    !in_array('izin', $absensiHariIni) &&
                    !in_array('sakit', $absensiHariIni);

            case 'sakit':
                // Can only report sick if haven't checked in yet and haven't taken leave/sick
                return !in_array('masuk', $absensiHariIni) &&
                    !in_array('izin', $absensiHariIni) &&
                    !in_array('sakit', $absensiHariIni);

            default:
                return false;
        }
    }

    private function getAddressFromCoordinates($latitude, $longitude)
    {
        // Simple implementation - you can integrate with Google Maps Geocoding API
        return "Lat: {$latitude}, Lng: {$longitude}";
    }

    /**
     * Validate if user is within office radius
     */
    private function validateLocationRadius($latitude, $longitude)
    {
        $officeLat = config('app.office.latitude');
        $officeLng = config('app.office.longitude');
        $maxRadius = config('app.office.radius_meters');

        $distance = $this->calculateDistance($officeLat, $officeLng, $latitude, $longitude);

        return $distance <= $maxRadius;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     * Returns distance in meters
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function riwayat()
    {
        $user = Auth::user();
        $absensi = Absensi::where('user_id', $user->id)
            ->orderBy('waktu_absen', 'desc')
            ->paginate(20);

        return view('pegawai.riwayat', compact('absensi'));
    }

    /**
     * Display kasbon page for employee
     */
    public function kasbon(Request $request)
    {
        $user = Auth::user();

        $query = Kasbon::where('user_id', $user->id);
        if ($request->status != "all") {
            // Filter berdasarkan status
            $query->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            });
        }

        // Filter berdasarkan tanggal dengan waktu menggunakan whereBetween
        if ($request->has('date_from') && $request->date_from && $request->has('date_to') && $request->date_to) {
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        } elseif ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from . ' 00:00:00');
        } elseif ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $kasbons = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pegawai.kasbon', compact('kasbons', 'user'));
    }

    /**
     * Show form to create kasbon for employee
     */
    public function kasbonCreate()
    {
        $user = Auth::user();
        return view('pegawai.kasbon-create', compact('user'));
    }

    /**
     * Show kasbon detail for employee
     */
    public function kasbonShow(Kasbon $kasbon)
    {
        // Ensure user can only view their own kasbon
        if ($kasbon->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $kasbon->load(['user', 'disetujui']);
        return view('pegawai.kasbon-show', compact('kasbon'));
    }

    /**
     * Display profile page for employee
     */
    public function profil()
    {
        $user = Auth::user();
        return view('pegawai.profil', compact('user'));
    }

    /**
     * Update profile for employee
     */
    public function profilUpdate(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'rekening' => 'nullable|string|max:50',
            'bank' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update basic information
            $user->name = $request->name;
            $user->rekening = $user->rekening ?? $request->rekening;
            $user->bank = $user->bank ?? $request->bank;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;

            // Handle password update
            if ($request->filled('password')) {
                // Verify current password if provided
                if ($request->filled('current_password')) {
                    if (!Hash::check($request->current_password, $user->password)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Password lama tidak sesuai'
                        ], 400);
                    }
                }
                $user->password = Hash::make($request->password);
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($user->photo && file_exists(public_path($user->photo))) {
                    unlink(public_path($user->photo));
                }

                // Convert and store photo as WebP
                $photo = $request->file('photo');
                $photoName = time() . '_' . pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';

                // Create image resource based on file type
                $imageType = exif_imagetype($photo->getPathname());
                switch ($imageType) {
                    case IMAGETYPE_JPEG:
                        $image = imagecreatefromjpeg($photo->getPathname());
                        break;
                    case IMAGETYPE_PNG:
                        $image = imagecreatefrompng($photo->getPathname());
                        break;
                    case IMAGETYPE_WEBP:
                        $image = imagecreatefromwebp($photo->getPathname());
                        break;
                    default:
                        return response()->json([
                            'success' => false,
                            'message' => 'Format gambar tidak didukung'
                        ], 400);
                }

                // Convert to WebP and save
                $photoPath = 'gambar/profile/' . $photoName;
                $fullPath = public_path($photoPath);

                // Ensure directory exists
                if (!file_exists(dirname($fullPath))) {
                    mkdir(dirname($fullPath), 0755, true);
                }

                imagewebp($image, $fullPath, 80); // 80% quality
                imagedestroy($image);

                $user->photo = $photoPath;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display salary slip list for employee
     */
    public function slipGaji(Request $request)
    {
        $user = Auth::user();

        $query = Gajian::where('user_id', $user->id);

        // Filter berdasarkan status
        if ($request->status && $request->status != "all") {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan periode gaji
        if ($request->has('periode_from') && $request->periode_from) {
            $query->where('periode_gaji', '>=', $request->periode_from);
        }
        if ($request->has('periode_to') && $request->periode_to) {
            $query->where('periode_gaji', '<=', $request->periode_to);
        }

        $slipGajis = $query->orderBy('periode_gaji', 'desc')->paginate(10);

        return view('pegawai.slip-gaji', compact('slipGajis', 'user'));
    }

    /**
     * Show detailed salary slip for employee
     */
    public function slipGajiShow(Gajian $gajian)
    {
        // Ensure user can only view their own salary slip
        if ($gajian->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $gajian->load(['user']);
        return view('pegawai.slip-gaji-detail', compact('gajian'));
    }

    /**
     * Print salary slip for employee
     */
    public function slipGajiPrint(Gajian $gajian)
    {
        // Ensure user can only print their own salary slip
        if ($gajian->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $gajian->load(['user']);
        return view('pegawai.slip-gaji-print', compact('gajian'));
    }

    public function kunjungan(Request $request)
    {
        $user = Auth::user();

        $query = KunjunganKerja::where('user_id', $user->id);

        // Prefer single input date range if provided: format "YYYY-MM-DD - YYYY-MM-DD"
        if ($request->filled('date_range')) {
            $range = $request->input('date_range');
            if (preg_match('/^\s*(\d{4}-\d{2}-\d{2})\s*[-–]\s*(\d{4}-\d{2}-\d{2})\s*$/', $range, $m)) {
                $from = $m[1];
                $to = $m[2];
                $query->whereBetween('tanggal_kunjungan', [$from, $to]);
            }
        } else {
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('tanggal_kunjungan', '>=', $request->date_from);
            }
            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('tanggal_kunjungan', '<=', $request->date_to);
            }
        }
        if ($request->has('client') && $request->client) {
            $query->where('client', 'like', '%' . $request->client . '%');
        }

        $kunjungans = $query->orderBy('tanggal_kunjungan', 'desc')->paginate(10);

        return view('pegawai.kunjungan', compact('kunjungans', 'user'));
    }

    public function kunjunganStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_kunjungan' => 'required|date',
            'client' => 'required|string|max:255',
            'ringkasan' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:5048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle photo upload - convert to WebP and store
        $foto = $request->file('foto');
        $fotoName = time() . '_' . pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';

        // Create image resource based on file type
        $imageType = exif_imagetype($foto->getPathname());
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($foto->getPathname());
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($foto->getPathname());
                // Preserve transparency for PNG
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case IMAGETYPE_WEBP:
                $image = imagecreatefromwebp($foto->getPathname());
                break;
            default:
                return back()->withErrors(['foto' => 'Format gambar tidak didukung'])->withInput();
        }

        $fotoPath = 'gambar/kunjungan/' . $fotoName;
        $fullPath = public_path($fotoPath);
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        imagewebp($image, $fullPath, 80); // 80% quality
        imagedestroy($image);

        KunjunganKerja::create([
            'user_id' => Auth::id(),
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'client' => $request->client,
            'ringkasan' => $request->ringkasan,
            'lokasi' => $request->lokasi,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('pegawai.kunjungan')->with('success', 'Kunjungan berhasil ditambahkan');
    }

    public function brosur(Request $request)
    {
        if ($request->ajax()) {
            $brosurs = \App\Models\Brosur::with('kategoriBrosur')
                ->where('status', 'aktif')
                ->when($request->has('kategori') && $request->kategori != 'all', function ($query) use ($request) {
                    // Filter berdasarkan tag JSON array
                    $query->whereJsonContains('tag', $request->kategori);
                })
                ->when($request->has('search') && $request->search, function ($query) use ($request) {
                    // Search dalam field tag (JSON array)
                    $query->whereJsonContains('tag', $request->search);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            return response()->json($brosurs);
        }

        return view('pegawai.katalog');
    }

    public function client(Request $request)
    {
        if ($request->ajax()) {
            $clients = Client::with(['projects'])->withCount('projects')->get();

            $clients->transform(function ($client) {
                // Collect project statuses without modifying the model directly
                $statuses = $client->projects->pluck('status')->toArray();

                // Add statuses as a new attribute to the response
                $client->project_statuses = $statuses;

                // Count projects by status
                $client->status_counts = $client->projects->groupBy('status')->map->count();

                return $client;
            });

            return response()->json($clients);
        }

        return view('pegawai.client');
    }
}
