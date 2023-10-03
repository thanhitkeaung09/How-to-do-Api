<?php

declare(strict_types=1);

namespace App\Services\FileStorage;

use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\UnableToCheckDirectoryExistence;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SpaceStorage implements FileStorageService
{
    public function clearCache($fileName): ClientResponse
    {
        return Http::asJson()->delete(
            config('filesystems.space.cdn_endpoint') . '/cache',
            [
                'files' => [$fileName],
            ]
        );
    }

    public function update(string $oldPath, string $link): bool
    {
        return Storage::put(
            path: $oldPath,
            contents: file_get_contents($link),
        );
    }

    public function put(string $folder, string $link): string
    {
        $name = $this->generateFileName();

        Storage::put(
            path: $folder . '/' . $name,
            contents: file_get_contents($link),
        );

        return $folder . '/' . $name;
    }

    public function upload(string $folder, UploadedFile | null $file): string
    {
        return Storage::putFileAs(
            path: $folder,
            file: $file,
            name: $this->generateFileName($file),
        );
    }

    public function display(string $path): HttpResponse
    {
        if (!$this->exists($path)) {
            throw new NotFoundHttpException('File not found!');
        }
        return $this->makeFileResponse($path);
    }

    public function delete(string $path): bool
    {
        if ($this->exists($path)) {
            return Storage::delete($path);
        }

        return true;
    }

    public function exists(string $path): bool
    {
        try {
            return Storage::exists($path);
        } catch (UnableToCheckDirectoryExistence) {
            return false;
        }
    }

    private function makeFileResponse(string $path): HttpResponse
    {
        $file = Storage::get($path);
        $type = Storage::mimeType($path);
        $response = Response::make($file, 200);
        $response->header('Content-Type', $type)->setMaxAge(604800)->setPrivate();
        return $response;
    }

    private function generateFileName(?UploadedFile $file = null): string
    {
        if (is_null($file)) {
            return (string) Str::uuid() . '.png';
        }
        return (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
    }
}
