<?php

namespace App\ImageKit;

use Illuminate\Support\Str;
use ImageKit\ImageKit;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\PathPrefixer;
use League\Flysystem\StorageAttributes;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToCreateDirectory;
use League\Flysystem\UnableToDeleteDirectory;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToRetrieveMetadata;
use League\Flysystem\UnableToSetVisibility;
use League\Flysystem\UnableToWriteFile;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use League\MimeTypeDetection\MimeTypeDetector;

class ImagekitAdapter implements FilesystemAdapter
{
    protected PathPrefixer $prefixer;

    public function __construct(
        protected ImageKit $client,
        string             $prefix = '',
        protected ?MimeTypeDetector  $mimeTypeDetector = null
    )
    {
        $this->prefixer = new PathPrefixer($prefix);
        $this->mimeTypeDetector = $mimeTypeDetector ?: new FinfoMimeTypeDetector();
    }

    public function getClient(): ImageKit
    {
        return $this->client;
    }

    public function fileExists(string $path): bool
    {
        try {
            return $this->searchFile($path)->type === 'file';
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function directoryExists(string $path): bool
    {
        try {
            return $this->searchFile($path, true)->type === 'folder';
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function write(string $path, string $contents, Config $config): void
    {
        $location = $this->applyPathPrefix($path);

        try {
            $this->client->upload([
                'file' => $contents,
                'fileName' => Str::afterLast($location, '/'),
                'useUniqueFileName' => false,
                'folder' => Str::beforeLast($location, '/')
            ]);
        } catch (\Exception $e) {
            throw UnableToWriteFile::atLocation($location, $e->getMessage(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function writeStream(string $path, $contents, Config $config): void
    {
        $location = $this->applyPathPrefix($path);

        try {
            $this->client->upload([
                'file' => $contents,
                'fileName' => Str::afterLast($location, '/'),
                'useUniqueFileName' => false,
                'folder' => Str::beforeLast($location, '/')
            ]);
        } catch (\Exception $e) {
            throw UnableToWriteFile::atLocation($location, $e->getMessage(), $e);
        }
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function read(string $path): string
    {
        throw new \Exception('Method [read] is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function readStream(string $path): string
    {
        $location = $this->applyPathPrefix($path);

        try {
            # $stream = $this->client->download($location);
            $stream = $this->read($path);
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($location, $e->getMessage(), $e);
        }

        return $stream;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path): void
    {
        $location = $this->applyPathPrefix($path);

        try {
            $this->client->deleteFile($this->searchFile($path)->fileId);
        } catch (\Exception $e) {
            throw UnableToDeleteFile::atLocation($location, $e->getMessage(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteDirectory(string $path): void
    {
        $location = $this->applyPathPrefix($path);

        try {
            $this->client->deleteFolder($location);
        } catch (UnableToDeleteFile $e) {
            throw UnableToDeleteDirectory::atLocation($location, $e->getPrevious()->getMessage(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function createDirectory(string $path, Config $config): void
    {
        $location = $this->applyPathPrefix($path);

        try {
            $this->client->createFolder(Str::afterLast($location, '/'), Str::beforeLast($location, '/'));
        } catch (\Exception $e) {
            throw UnableToCreateDirectory::atLocation($location, $e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function setVisibility(string $path, string $visibility): void
    {
        throw UnableToSetVisibility::atLocation($path, 'Adapter does not support visibility controls.');
    }

    /**
     * @inheritDoc
     */
    public function visibility(string $path): FileAttributes
    {
        // Noop
        return new FileAttributes($path);
    }

    /**
     * @inheritDoc
     */
    public function mimeType(string $path): FileAttributes
    {
        return new FileAttributes($path,null,null,null,
            $this->mimeTypeDetector->detectMimeTypeFromPath($path)
        );
    }

    /**
     * @inheritDoc
     */
    public function lastModified(string $path): FileAttributes
    {
        $location = $this->applyPathPrefix($path);

        try {
            $file = $this->searchFile($path, true);
        } catch (\Exception $e) {
            throw UnableToRetrieveMetadata::lastModified($location, $e->getMessage());
        }

        return new FileAttributes($path, null, null, strtotime($file->updatedAt));
    }

    /**
     * @inheritDoc
     */
    public function fileSize(string $path): FileAttributes
    {
        $location = $this->applyPathPrefix($path);

        try {
            $file = $this->searchFile($path);
        } catch (\Exception $e) {
            throw UnableToRetrieveMetadata::fileSize($location, $e->getMessage());
        }

        return new FileAttributes($path, $file->size);
    }

    /**
     * {@inheritDoc}
     */
    public function listContents(string $path = '', bool $deep = false): iterable
    {
        foreach ($this->iterateFolderContents($path, $deep) as $entry) {
            $storageAttrs = $this->normalizeResponse($entry);

            // Avoid including the base directory itself
            if ($storageAttrs->isDir() && $storageAttrs->path() === $path) {
                continue;
            }

            yield $storageAttrs;
        }
    }

    protected function iterateFolderContents(string $path = '', bool $deep = false): \Generator
    {
        $location = $this->applyPathPrefix($path);

        try {
            $result = $this->client->listFolder($location, $deep);
        } catch (\Exception $e) {
            return;
        }

        yield from $result['entries'];

        while ($result['has_more']) {
            $result = $this->client->listFolderContinue($result['cursor']);
            yield from $result['entries'];
        }
    }

    protected function normalizeResponse(array $response): StorageAttributes
    {
        $timestamp = (isset($response['server_modified'])) ? strtotime($response['server_modified']) : null;

        if ($response['.tag'] === 'folder') {
            $normalizedPath = ltrim($this->prefixer->stripDirectoryPrefix($response['path_display']), '/');

            return new DirectoryAttributes(
                $normalizedPath,
                null,
                $timestamp
            );
        }

        $normalizedPath = ltrim($this->prefixer->stripPrefix($response['path_display']), '/');

        return new FileAttributes(
            $normalizedPath,
            $response['size'] ?? null,
            null,
            $timestamp,
            $this->mimeTypeDetector->detectMimeTypeFromPath($normalizedPath)
        );
    }

    /**
     * @inheritDoc
     */
    public function move(string $source, string $destination, Config $config): void
    {
        $path = $this->applyPathPrefix($source);
        $newPath = $this->applyPathPrefix($destination);

        try {
            $type = $this->searchFile($path, true)->type;
            $this->client->{'move' . ucfirst($type)}($path, $newPath);
        } catch (\Exception $e) {
            throw UnableToMoveFile::fromLocationTo($path, $newPath, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function copy(string $source, string $destination, Config $config): void
    {
        $path = $this->applyPathPrefix($source);
        $newPath = $this->applyPathPrefix($destination);

        try {
            $type = $this->searchFile($path, true)->type;
            $this->client->{'copy' . ucfirst($type)}($path, $newPath);
        } catch (\Exception $e) {
            throw UnableToCopyFile::fromLocationTo($path, $newPath, $e);
        }
    }

    protected function applyPathPrefix($path): string
    {
        return '/' . trim($this->prefixer->prefixPath($path), '/');
    }


    public function getUrl(string $path): string
    {
        return $this->client->url([
            'path' => $this->applyPathPrefix($path),
        ]);
    }

    /**
     * @param string $path
     *
     * Search for a file or directory by name/path
     */
    public function searchFile(string $path, bool $includeFolder = false)
    {
        $location = $this->applyPathPrefix($path);

        $file = $this->client->listFiles([
            'name' => Str::afterLast($location, '/'),
            'path' => Str::beforeLast($location, '/'),
            'includeFolder' => $includeFolder,
        ]);

        return data_get($file->success, 0);
    }

}
