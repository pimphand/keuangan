<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan Geolocation - Absensi Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .help-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .step-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin: 15px 0;
            border-left: 5px solid #007bff;
        }

        .browser-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="help-card p-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>Bantuan Geolocation
                        </h2>
                        <p class="text-muted">Cara mengaktifkan deteksi lokasi untuk absensi</p>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Mengapa lokasi diperlukan?</strong><br>
                        Sistem absensi memerlukan lokasi untuk memastikan keabsahan absensi dan mencegah kecurangan.
                    </div>

                    <h5 class="mt-4 mb-3"><i class="fas fa-lock me-2"></i>Persyaratan Keamanan</h5>
                    <div class="step-card">
                        <h6><i class="fas fa-shield-alt me-2"></i>HTTPS Diperlukan</h6>
                        <p>Browser modern memerlukan koneksi HTTPS untuk mengakses geolocation. Beberapa solusi:</p>
                        <ul>
                            <li><strong>Localhost:</strong> Gunakan <code>http://localhost</code> untuk development</li>
                            <li><strong>HTTPS:</strong> Deploy aplikasi dengan sertifikat SSL</li>
                            <li><strong>Chrome Flags:</strong> Aktifkan "Insecure origins treated as secure" (tidak
                                disarankan untuk production)</li>
                        </ul>
                    </div>

                    <h5 class="mt-4 mb-3"><i class="fas fa-mobile-alt me-2"></i>Panduan Browser Mobile</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="step-card">
                                <div class="text-center">
                                    <i class="fab fa-chrome browser-icon text-success"></i>
                                    <h6>Google Chrome</h6>
                                </div>
                                <ol>
                                    <li>Buka menu Chrome (⋮)</li>
                                    <li>Pilih "Settings"</li>
                                    <li>Tap "Privacy and security"</li>
                                    <li>Pilih "Site settings"</li>
                                    <li>Tap "Location"</li>
                                    <li>Pastikan "Ask before accessing" aktif</li>
                                </ol>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="step-card">
                                <div class="text-center">
                                    <i class="fab fa-safari browser-icon text-primary"></i>
                                    <h6>Safari (iOS)</h6>
                                </div>
                                <ol>
                                    <li>Buka "Settings" di iPhone</li>
                                    <li>Scroll ke "Safari"</li>
                                    <li>Tap "Location Services"</li>
                                    <li>Pastikan "Safari" diizinkan</li>
                                    <li>Kembali ke Safari</li>
                                    <li>Refresh halaman absensi</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="step-card">
                                <div class="text-center">
                                    <i class="fab fa-firefox browser-icon text-warning"></i>
                                    <h6>Firefox</h6>
                                </div>
                                <ol>
                                    <li>Buka menu Firefox (☰)</li>
                                    <li>Pilih "Settings"</li>
                                    <li>Scroll ke "Privacy & Security"</li>
                                    <li>Cari "Permissions"</li>
                                    <li>Tap "Location"</li>
                                    <li>Pilih "Ask" atau "Allow"</li>
                                </ol>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="step-card">
                                <div class="text-center">
                                    <i class="fab fa-edge browser-icon text-info"></i>
                                    <h6>Microsoft Edge</h6>
                                </div>
                                <ol>
                                    <li>Buka menu Edge (⋯)</li>
                                    <li>Pilih "Settings"</li>
                                    <li>Tap "Site permissions"</li>
                                    <li>Pilih "Location"</li>
                                    <li>Pastikan "Ask before accessing" aktif</li>
                                    <li>Refresh halaman</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Tips Penting:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Pastikan GPS/Location Services aktif di pengaturan perangkat</li>
                            <li>Izinkan akses lokasi ketika browser meminta</li>
                            <li>Jika masih bermasalah, coba restart browser</li>
                            <li>Absensi tetap bisa dilakukan tanpa lokasi, namun akan ditandai sebagai "tanpa lokasi"
                            </li>
                        </ul>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('pegawai.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Absensi
                        </a>
                        <button class="btn btn-outline-primary ms-2" onclick="location.reload()">
                            <i class="fas fa-redo me-2"></i>Coba Lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>