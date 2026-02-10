<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Convertir y guardar imagen en formato WebP
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $quality
     * @param int|null $maxWidth
     * @param int|null $maxHeight
     * @return string|null Ruta del archivo guardado
     */
    public function convertToWebP(
        UploadedFile $file,
        string $directory = 'profiles',
        int $quality = 80,
        ?int $maxWidth = 1200,
        ?int $maxHeight = 1200
    ): ?string {
        try {
            // Verificar que GD esté disponible
            if (!extension_loaded('gd')) {
                Log::warning('ImageService: Extensión GD no disponible, guardando imagen original');
                return $file->store($directory, 'public');
            }

            // Obtener información de la imagen
            $imageInfo = getimagesize($file->getPathname());
            if (!$imageInfo) {
                Log::warning('ImageService: No se pudo obtener información de la imagen');
                return $file->store($directory, 'public');
            }

            $mimeType = $imageInfo['mime'];
            $originalWidth = $imageInfo[0];
            $originalHeight = $imageInfo[1];

            // Crear imagen según el tipo
            $sourceImage = $this->createImageFromFile($file->getPathname(), $mimeType);
            if (!$sourceImage) {
                Log::warning('ImageService: No se pudo crear imagen desde archivo');
                return $file->store($directory, 'public');
            }

            // Calcular nuevas dimensiones manteniendo proporción
            [$newWidth, $newHeight] = $this->calculateDimensions(
                $originalWidth,
                $originalHeight,
                $maxWidth,
                $maxHeight
            );

            // Redimensionar si es necesario
            if ($newWidth !== $originalWidth || $newHeight !== $originalHeight) {
                $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

                // Preservar transparencia para PNG
                if ($mimeType === 'image/png') {
                    imagealphablending($resizedImage, false);
                    imagesavealpha($resizedImage, true);
                    $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                    imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
                }

                imagecopyresampled(
                    $resizedImage,
                    $sourceImage,
                    0, 0, 0, 0,
                    $newWidth, $newHeight,
                    $originalWidth, $originalHeight
                );

                imagedestroy($sourceImage);
                $sourceImage = $resizedImage;
            }

            // Generar nombre único
            $filename = Str::uuid() . '.webp';
            $relativePath = $directory . '/' . $filename;
            $absolutePath = storage_path('app/public/' . $relativePath);

            // Asegurar que el directorio existe
            $dirPath = dirname($absolutePath);
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0755, true);
            }

            // Convertir a WebP
            $success = imagewebp($sourceImage, $absolutePath, $quality);

            // Liberar memoria
            imagedestroy($sourceImage);

            if ($success) {
                Log::info('ImageService: Imagen convertida a WebP', [
                    'original_size' => $file->getSize(),
                    'original_dimensions' => "{$originalWidth}x{$originalHeight}",
                    'new_dimensions' => "{$newWidth}x{$newHeight}",
                    'new_size' => filesize($absolutePath),
                    'path' => $relativePath
                ]);
                return $relativePath;
            }

            Log::error('ImageService: Error al guardar WebP');
            return $file->store($directory, 'public');

        } catch (\Exception $e) {
            Log::error('ImageService: Excepción al convertir imagen', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            // Fallback: guardar original
            return $file->store($directory, 'public');
        }
    }

    /**
     * Crear recurso de imagen desde archivo
     */
    private function createImageFromFile(string $path, string $mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/gif':
                return imagecreatefromgif($path);
            case 'image/webp':
                return imagecreatefromwebp($path);
            case 'image/bmp':
                return imagecreatefrombmp($path);
            default:
                return null;
        }
    }

    /**
     * Calcular dimensiones manteniendo proporción
     */
    private function calculateDimensions(
        int $width,
        int $height,
        ?int $maxWidth,
        ?int $maxHeight
    ): array {
        if (!$maxWidth && !$maxHeight) {
            return [$width, $height];
        }

        $ratio = $width / $height;

        if ($maxWidth && $width > $maxWidth) {
            $width = $maxWidth;
            $height = (int) ($width / $ratio);
        }

        if ($maxHeight && $height > $maxHeight) {
            $height = $maxHeight;
            $width = (int) ($height * $ratio);
        }

        return [$width, $height];
    }

    /**
     * Eliminar imagen
     */
    public function delete(string $path): bool
    {
        if (!$path || str_contains($path, 'pravatar') || str_starts_with($path, 'http')) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }
}
