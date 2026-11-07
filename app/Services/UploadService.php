<?php
declare(strict_types=1);
namespace App\Services;
use RuntimeException;
final class UploadService
{
    public function store(array $file, string $folder): string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload failed.');
        }
        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            throw new RuntimeException('Maximum upload size is 5 MB.');
        }
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!isset($extensions[$mime])) {
            throw new RuntimeException('Only JPG, PNG and WebP images are accepted.');
        }
        $base = dirname(__DIR__, 2) . '/storage/uploads/' . trim($folder, '/');
        if (!is_dir($base) && !mkdir($base, 0750, true) && !is_dir($base)) {
            throw new RuntimeException('Cannot create upload directory.');
        }
        $name = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
        if (!move_uploaded_file($file['tmp_name'], $base . '/' . $name)) {
            throw new RuntimeException('Cannot store uploaded file.');
        }
        return 'storage/uploads/' . trim($folder, '/') . '/' . $name;
    }
}
