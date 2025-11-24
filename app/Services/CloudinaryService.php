<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * Delete an image from Cloudinary
     *
     * @param string $imageUrl The full URL of the image to delete
     * @return bool
     */
    public static function deleteImage(string $imageUrl): bool
    {
        try {
            // Extract public_id from the Cloudinary URL
            $publicId = self::extractPublicId($imageUrl);

            if (!$publicId) {
                Log::warning('Could not extract public_id from URL: ' . $imageUrl);
                return false;
            }

            $cloudName = config('cloudinary.cloud_name');
            $apiKey = config('cloudinary.api_key');
            $apiSecret = config('cloudinary.api_secret');

            if (!$cloudName || !$apiKey || !$apiSecret) {
                Log::error('Cloudinary credentials not configured');
                return false;
            }

            // Create signature for API request
            $timestamp = time();
            $signature = self::generateSignature($publicId, $timestamp, $apiSecret);

            // Make API request to delete
            $response = Http::asForm()->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy", [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
                'api_key' => $apiKey,
                'signature' => $signature,
            ]);

            if ($response->successful()) {
                Log::info('Successfully deleted image from Cloudinary: ' . $publicId);
                return true;
            }

            Log::warning('Failed to delete image from Cloudinary: ' . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error('Error deleting Cloudinary image: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete multiple images from Cloudinary
     *
     * @param array $imageUrls Array of image URLs to delete
     * @return int Number of successfully deleted images
     */
    public static function deleteImages(array $imageUrls): int
    {
        $deletedCount = 0;

        foreach ($imageUrls as $imageUrl) {
            if (self::deleteImage($imageUrl)) {
                $deletedCount++;
            }
        }

        return $deletedCount;
    }

    /**
     * Extract public_id from Cloudinary URL
     *
     * @param string $url
     * @return string|null
     */
    private static function extractPublicId(string $url): ?string
    {
        // Example URL: https://res.cloudinary.com/cloud_name/image/upload/v1234567890/triply/image.jpg
        // We want: triply/image

        // Match everything after /upload/ and before the file extension
        if (preg_match('/\/upload\/(?:v\d+\/)?(.+)\.[a-z]+$/i', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Generate signature for Cloudinary API
     *
     * @param string $publicId
     * @param int $timestamp
     * @param string $apiSecret
     * @return string
     */
    private static function generateSignature(string $publicId, int $timestamp, string $apiSecret): string
    {
        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];

        ksort($params);

        $signatureString = http_build_query($params, '', '&', PHP_QUERY_RFC3986);

        return sha1($signatureString . $apiSecret);
    }
}
