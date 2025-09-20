<!-- Camera Section Component -->
<div class="mb-6">
    <h6 class="font-semibold text-gray-700 mb-3">
        <i class="fas fa-camera mr-2"></i>Foto Absensi
    </h6>
    <div class="relative bg-gray-50 rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 min-h-[200px] flex items-center justify-center"
        id="camera-container">
        <div id="webcam-container" style="display: none;" class="w-full">
            <div id="webcam-preview" class="w-full h-[200px] rounded-2xl overflow-hidden bg-black">
            </div>
            <div class="text-center mt-3">
                <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg mr-2 transition-colors"
                    onclick="capturePhoto()">
                    <i class="fas fa-camera mr-1"></i>Ambil Foto
                </button>
                <button class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors"
                    onclick="stopWebcam()">
                    <i class="fas fa-times mr-1"></i>Tutup Kamera
                </button>
            </div>
        </div>
        <div id="camera-placeholder" class="text-center">
            <i class="fas fa-camera text-4xl text-gray-400 mb-3"></i>
            <p class="text-gray-500 mb-3">Klik untuk membuka kamera</p>
            <button
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg border border-blue-500 transition-colors"
                onclick="startWebcam()">
                <i class="fas fa-camera mr-2"></i>Buka Kamera
            </button>
        </div>
    </div>
    <div id="captured-preview" style="display: none;">
        <img id="captured-image" class="w-full h-[200px] object-cover rounded-2xl" alt="Captured Photo">
        <div class="text-center mt-2">
            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm mr-2 transition-colors"
                onclick="retakePhoto()">
                <i class="fas fa-redo mr-1"></i>Ambil Ulang
            </button>
            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition-colors"
                onclick="usePhoto()">
                <i class="fas fa-check mr-1"></i>Gunakan Foto
            </button>
        </div>
    </div>
</div>