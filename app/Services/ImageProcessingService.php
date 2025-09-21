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
