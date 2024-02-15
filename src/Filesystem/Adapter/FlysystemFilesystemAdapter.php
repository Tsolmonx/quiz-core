<?php

declare(strict_types=1);

namespace App\Filesystem\Adapter;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class FlysystemFilesystemAdapter
{
    public function __construct(private FilesystemOperator $filesystem)
    {
    }

    public function has(string $location): bool
    {
        return $this->filesystem->fileExists($location);
    }

    public function write(string $location, string $content): void
    {
        $this->filesystem->write($location, $content);
    }

    public function delete(string $location): void
    {
        if (!$this->has($location)) {
            throw new NotFoundHttpException($location . ' file not found');
        }

        $this->filesystem->delete($location);
    }
}
