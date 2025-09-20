<!-- Camera Script Component -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script>
    let capturedPhotoData = null;
    let isWebcamActive = false;
    let selectedFile = null;

    // Webcam.js functionality
    function startWebcam() {
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90,
            constraints: {
                facingMode: "environment" // Use back camera if available
            }
        });

        Webcam.attach('#webcam-preview');

        document.getElementById('camera-placeholder').style.display = 'none';
        document.getElementById('webcam-container').style.display = 'block';
        document.getElementById('captured-preview').style.display = 'none';
        isWebcamActive = true;
    }

    function stopWebcam() {
        if (isWebcamActive) {
            Webcam.reset();
            document.getElementById('webcam-container').style.display = 'none';
            document.getElementById('camera-placeholder').style.display = 'block';
            isWebcamActive = false;
        }
    }

    function capturePhoto() {
        Webcam.snap(function (data_uri) {
            capturedPhotoData = data_uri;
            document.getElementById('captured-image').src = data_uri;
            document.getElementById('webcam-container').style.display = 'none';
            document.getElementById('captured-preview').style.display = 'block';
            Webcam.reset();
            isWebcamActive = false;
        });
    }

    function retakePhoto() {
        document.getElementById('captured-preview').style.display = 'none';
        document.getElementById('camera-placeholder').style.display = 'block';
        capturedPhotoData = null;
    }

    function usePhoto() {
        if (capturedPhotoData) {
            // Convert data URI to blob for form submission
            const byteString = atob(capturedPhotoData.split(',')[1]);
            const mimeString = capturedPhotoData.split(',')[0].split(':')[1].split(';')[0];
            const ab = new ArrayBuffer(byteString.length);
            const ia = new Uint8Array(ab);
            for (let i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            const blob = new Blob([ab], { type: mimeString });
            selectedFile = blob;

            // Hide the preview and show success message
            document.getElementById('captured-preview').style.display = 'none';
            alert('Foto berhasil dipilih! Anda dapat melanjutkan absensi.');
        }
    }

    function resetCamera() {
        capturedPhotoData = null;
        selectedFile = null;
        stopWebcam();
        document.getElementById('captured-preview').style.display = 'none';
        document.getElementById('camera-placeholder').style.display = 'block';
    }
</script>