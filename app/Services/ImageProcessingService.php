<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageProcessingService
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Process and convert image to WebP with compression
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $quality (1-100, where 40 = 40% quality)
     * @return string
     */
    public function processAndStore(UploadedFile $file, string $directory = 'images', int $quality = 40): string
    {
        // Generate unique filename with .webp extension
        $filename = Str::random(10) . '-' . Str::random(5) . '.webp';
        $path = $directory . '/' . $filename;

        // Process the image
        $image = $this->manager->read($file->getPathname());

        // Resize if image is too large (optional - you can adjust max dimensions)
        $image = $this->resizeIfNeeded($image, 1920, 1080);

        // Convert to WebP and compress
        $webpData = $image->toWebp($quality);

        // Store the processed image directly to public folder
        $fullPath = public_path($path);

        // Ensure directory exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        file_put_contents($fullPath, $webpData);

        return $path;
    }

    /**
     * Process base64 data URL or raw base64 image string and store as WebP in public
     *
     * @param string $base64Data data URL (e.g., data:image/jpeg;base64,...) or raw base64
     * @param string $directory directory under public
     * @param int $quality WebP quality (1-100)
     * @return string relative public path (e.g., gambar/ktp/abc.webp)
     */
    public function processBase64AndStore(string $base64Data, string $directory = 'images', int $quality = 40): string
    {
        // Strip data URL prefix if present
        if (str_starts_with($base64Data, 'data:')) {
            $parts = explode(',', $base64Data, 2);
            $base64Data = $parts[1] ?? '';
        }

        $binary = base64_decode($base64Data, true);
        if ($binary === false) {
            throw new \InvalidArgumentException('Invalid base64 image data');
        }

        $filename = Str::random(10) . '-' . Str::random(5) . '.webp';
        $path = rtrim($directory, '/') . '/' . $filename;
        $fullPath = public_path($path);

        $image = $this->manager->read($binary);
        $image = $this->resizeIfNeeded($image, 1920, 1080);
        $webpData = $image->toWebp($quality);

        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        file_put_contents($fullPath, $webpData);

        return $path;
    }

    /**
     * Resize image if it exceeds maximum dimensions while maintaining aspect ratio
     *
     * @param \Intervention\Image\Image $image
     * @param int $maxWidth
     * @param int $maxHeight
     * @return \Intervention\Image\Image
     */
    protected function resizeIfNeeded($image, int $maxWidth, int $maxHeight)
    {
        $width = $image->width();
        $height = $image->height();

        // Only resize if image is larger than maximum dimensions
        if ($width > $maxWidth || $height > $maxHeight) {
            $image->scaleDown($maxWidth, $maxHeight);
        }

        return $image;
    }

    /**
     * Get file size in human readable format
     *
     * @param string $path
     * @return string
     */
    public function getFileSize(string $path): string
    {
        $fullPath = public_path($path);
        if (file_exists($fullPath)) {
            $size = filesize($fullPath);
            return $this->formatBytes($size);
        }
        return '0 B';
    }

    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Delete image file
     *
     * @param string $path
     * @return bool
     */
    public function deleteImage(string $path): bool
    {
        $fullPath = public_path($path);
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }
}
