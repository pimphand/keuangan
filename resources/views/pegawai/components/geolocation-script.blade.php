<!-- Geolocation Script Component -->
<script>
    let currentLocation = null;

    // Office location configuration (from Laravel config)
    const officeLocation = {
        latitude: {{ config('app.office.latitude') }},
        longitude: {{ config('app.office.longitude') }},
        radiusMeters: {{ config('app.office.radius_meters') }}
    };

    // Calculate distance between two coordinates using Haversine formula
    function calculateDistance(lat1, lng1, lat2, lng2) {
        const earthRadius = 6371000; // Earth's radius in meters

        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;

        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLng / 2) * Math.sin(dLng / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return earthRadius * c; // Distance in meters
    }

    // Format distance for display
    function formatDistance(meters) {
        if (meters < 1000) {
            return Math.round(meters) + ' meter';
        } else {
            return (meters / 1000).toFixed(1) + ' km';
        }
    }

    // Get current location with proper error handling
    function getCurrentLocation() {
        if (navigator.geolocation) {
            // Check if we're in a secure context
            if (window.isSecureContext || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        currentLocation = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        };
                        updateLocationInfo();
                    },
                    function (error) {
                        handleLocationError(error);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 300000
                    }
                );
            } else {
                showLocationError('Geolocation memerlukan HTTPS. Silakan gunakan HTTPS atau localhost.');
            }
        } else {
            showLocationError('Browser tidak mendukung geolocation');
        }
    }

    function handleLocationError(error) {
        let errorMessage = '';
        switch (error.code) {
            case error.PERMISSION_DENIED:
                errorMessage = 'Akses lokasi ditolak. Silakan izinkan akses lokasi di browser.';
                break;
            case error.POSITION_UNAVAILABLE:
                errorMessage = 'Informasi lokasi tidak tersedia.';
                break;
            case error.TIMEOUT:
                errorMessage = 'Permintaan lokasi timeout.';
                break;
            default:
                errorMessage = 'Terjadi kesalahan saat mengambil lokasi.';
                break;
        }
        showLocationError(errorMessage);
    }

    function showLocationError(message) {
        document.getElementById('location-info').innerHTML =
            '<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">' +
            '<i class="fas fa-exclamation-triangle mr-2 text-yellow-600"></i>' + message +
            '<br><small class="mt-2 text-yellow-700">Anda masih bisa melakukan absensi, namun lokasi tidak akan tercatat.</small>' +
            '<div class="mt-3 flex space-x-2">' +
            '<button class="px-3 py-1 text-sm border border-blue-500 text-blue-600 rounded hover:bg-blue-50 transition-colors" onclick="getCurrentLocation()">' +
            '<i class="fas fa-redo mr-1"></i>Coba Lagi</button>' +
            '<a href="{{ route("pegawai.geolocation-help") }}" class="px-3 py-1 text-sm border border-cyan-500 text-cyan-600 rounded hover:bg-cyan-50 transition-colors">' +
            '<i class="fas fa-question-circle mr-1"></i>Bantuan</a>' +
            '</div>' +
            '</div>';
    }

    function updateLocationInfo() {
        if (currentLocation) {
            // Calculate distance from office
            const distance = calculateDistance(
                officeLocation.latitude,
                officeLocation.longitude,
                currentLocation.latitude,
                currentLocation.longitude
            );

            const isWithinRadius = distance <= officeLocation.radiusMeters;
            const distanceText = formatDistance(distance);

            // Create status indicator
            const statusIcon = isWithinRadius ?
                '<i class="fas fa-check-circle text-green-500"></i>' :
                '<i class="fas fa-exclamation-triangle text-red-500"></i>';

            const statusText = isWithinRadius ?
                '<span class="text-green-600 font-medium">Dalam radius kantor</span>' :
                '<span class="text-red-600 font-medium">Di luar radius kantor</span>';

            document.getElementById('location-info').innerHTML =
                `<div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                            <span class="text-sm text-gray-700">Lokasi saat ini</span>
                        </div>
                        ${statusIcon}
                    </div>
                    <div class="text-xs text-gray-600 pl-6">
                        Lat: ${currentLocation.latitude.toFixed(6)}, Lng: ${currentLocation.longitude.toFixed(6)}
                    </div>
                    <div class="flex items-center justify-between pl-6">
                        <div class="flex items-center">
                            <i class="fas fa-building text-gray-500 mr-2"></i>
                            <span class="text-sm text-gray-700">Jarak dari kantor: <strong>${distanceText}</strong></span>
                        </div>
                        ${statusText}
                    </div>
                    <div class="text-xs text-gray-500 pl-6">
                        Radius maksimal: ${officeLocation.radiusMeters} meter
                    </div>
                </div>`;
        }
    }

    // Initialize location when component is loaded
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('location-info')) {
            getCurrentLocation();
        }
    });
</script>