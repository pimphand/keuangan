<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class GeoJsonController extends Controller
{
    /**
     * Store a GeoJSON payload (polygon + properties or full GeoJSON) to disk.
     */
    public function store(Request $request): JsonResponse
    {
        // Accept either a full GeoJSON object under `geojson`,
        // or a `polygon` coordinates array with optional `properties`.
        $request->validate([
            'geojson' => ['nullable', 'array'],
            'polygon' => ['nullable', 'array'],
            'properties' => ['nullable', 'array'],
            'filename' => ['nullable', 'string'],
        ]);

        $geojson = $request->input('geojson');

        if (!$geojson) {
            $polygonCoordinates = $request->input('polygon');
            $properties = $request->input('properties', []);

            if (!is_array($polygonCoordinates) || empty($polygonCoordinates)) {
                return response()->json([
                    'message' => 'Invalid input: provide either `geojson` or a non-empty `polygon` coordinates array.',
                ], 422);
            }

            // Ensure coordinates are in proper GeoJSON Polygon format: [ [ [lng, lat], ... ] ]
            // If the first and last coordinate are not the same, close the ring automatically.
            $ring = $polygonCoordinates;
            if ($this->needsClosing($ring)) {
                $ring[] = $ring[0];
            }

            $geojson = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [$ring],
                ],
                'properties' => $properties,
            ];
        }

        // Determine filename
        $filename = $request->input('filename');
        if (!$filename) {
            $slug = isset($geojson['properties']['name']) && is_string($geojson['properties']['name'])
                ? Str::slug($geojson['properties']['name'])
                : 'feature';
            $filename = $slug . '-' . now()->format('Ymd-His') . '.json';
        } else {
            if (!Str::endsWith($filename, '.json')) {
                $filename .= '.json';
            }
        }

        // Persist into public/data-map to align with existing map assets
        $directory = public_path('data-map');
        File::ensureDirectoryExists($directory);
        $absolutePath = $directory . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($absolutePath, json_encode($geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return response()->json([
            'message' => 'GeoJSON saved successfully.',
            'path' => url('data-map/' . $filename),
            'filename' => $filename,
        ], 201);
    }

    private function needsClosing(array $ring): bool
    {
        if (count($ring) < 3) {
            return false;
        }
        $first = $ring[0] ?? null;
        $last = $ring[count($ring) - 1] ?? null;
        return $first !== $last;
    }
}
