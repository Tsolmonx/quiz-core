<?php

declare(strict_types=1);

namespace App\Service;

use App\Filesystem\Adapter\FlysystemFilesystemAdapter;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploaderService
{
    public function __construct(
        private FlysystemFilesystemAdapter $filesystem,
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $fileContents = file_get_contents($file->getPathname());

        do {
            $hash = bin2hex(random_bytes(16));
            $path = $this->expandPath($hash . '.' . $file->guessExtension());
        } while ($this->filesystem->has($path));

        try {
            $this->filesystem->write(
                $path,
                $fileContents,
            );
        } catch (FileException $e) {
            return '';
        }

        return $path;
    }

    private function expandPath(
        string $path
    ): string {
        return sprintf(
            '%s/%s/%s',
            substr($path, 0, 2),
            substr($path, 2, 2),
            $originalName ?? substr($path, 4),
        );
    }

    public function deleteImage(string $path): bool
    {
        try {
            $this->filesystem->delete($path);
            return true;
        } catch (FilesystemException $e) {
            return false;
        }
    }
}
