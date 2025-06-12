<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUploadService
{
    /**
     * Upload a media file to the specified directory.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $type - e.g. image, video, document
     * @return string|null - file path or null on failure
     */
    public function upload(UploadedFile $file, string $directory, string $type): ?string
    {
        if (!$this->isValidType($file, $type)) {
            return null;
        }

        $filename = $this->generateFilename($file);
        $path = "$directory/$filename";

        Storage::disk('public')->putFileAs($directory, $file, $filename);

        return "storage/$path";
    }

    /**
     * Delete a file from storage.
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        $relativePath = str_replace('storage/', '', $path);
        return Storage::disk('public')->delete($relativePath);
    }

    /**
     * Generate a unique filename with extension.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFilename(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Validate the file type based on category.
     *
     * @param UploadedFile $file
     * @param string $type
     * @return bool
     */
    protected function isValidType(UploadedFile $file, string $type): bool
    {
        $mime = $file->getMimeType();

        $allowedTypes = [
            'image' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
            'video' => ['video/mp4', 'video/quicktime'],
            'document' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'audio' => ['audio/mpeg', 'audio/wav'],
        ];

        return in_array($mime, $allowedTypes[$type] ?? []);
    }
}
