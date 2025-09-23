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

        // Normalize input into a list of Feature(s) we want to append
        $featuresToAppend = [];
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

            $feature = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [$ring],
                ],
                'properties' => $properties,
            ];
            $featuresToAppend[] = $feature;
        } else {
            // If client passed a full GeoJSON payload, support Feature or FeatureCollection
            $type = $geojson['type'] ?? null;
            if ($type === 'Feature') {
                $featuresToAppend[] = $geojson;
            } elseif ($type === 'FeatureCollection' && isset($geojson['features']) && is_array($geojson['features'])) {
                foreach ($geojson['features'] as $feat) {
                    if (is_array($feat) && ($feat['type'] ?? null) === 'Feature') {
                        $featuresToAppend[] = $feat;
                    }
                }
            } else {
                // Primitive geometry case: wrap into Feature
                if (isset($geojson['coordinates']) && isset($geojson['type'])) {
                    $featuresToAppend[] = [
                        'type' => 'Feature',
                        'geometry' => $geojson,
                        'properties' => $request->input('properties', []),
                    ];
                } else {
                    return response()->json([
                        'message' => 'Invalid geojson payload. Expect a Feature, FeatureCollection, or a geometry object.',
                    ], 422);
                }
            }
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

        // If target is desa_batukuta.geojson, append Feature(s) to existing FeatureCollection
        if (Str::lower($filename) === 'desa_batukuta.geojson') {
            try {
                $existing = [];
                if (File::exists($absolutePath)) {
                    $decoded = json_decode(File::get($absolutePath), true);
                    if (is_array($decoded) && ($decoded['type'] ?? null) === 'FeatureCollection') {
                        $existing = $decoded;
                    }
                }

                if (!isset($existing['type']) || $existing['type'] !== 'FeatureCollection') {
                    $existing = ['type' => 'FeatureCollection', 'features' => []];
                }
                if (!isset($existing['features']) || !is_array($existing['features'])) {
                    $existing['features'] = [];
                }

                foreach ($featuresToAppend as $feat) {
                    // Basic validation: ensure geometry exists
                    if (!isset($feat['geometry']) || !isset($feat['geometry']['type']) || !isset($feat['geometry']['coordinates'])) {
                        // skip invalid feature
                        continue;
                    }
                    $existing['features'][] = $feat;
                }
                File::put($absolutePath, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

                return response()->json([
                    'message' => 'Feature(s) appended to desa_batukuta.geojson successfully.',
                    'path' => url('data-map/' . $filename),
                    'filename' => $filename,
                    'features_appended' => count($featuresToAppend),
                ], 201);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => 'Failed to append feature: ' . $e->getMessage(),
                ], 500);
            }
        }

        // Default behavior: if a single feature was provided, write that; otherwise write collection
        $toWrite = count($featuresToAppend) === 1
            ? $featuresToAppend[0]
            : ['type' => 'FeatureCollection', 'features' => $featuresToAppend];
        file_put_contents($absolutePath, json_encode($toWrite, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

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
