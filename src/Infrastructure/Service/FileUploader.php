<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Service\FileUploaderInterface;
use App\Domain\ValueObject\UploadedFileInformation;
use App\Infrastructure\Bridge\Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class FileUploader implements FileUploaderInterface
{
    public const string UPLOADED_PROFILE_PICTURES_DIRECTORY = '/uploads/profile_pictures';

    public function __construct(private string $publicDirectory)
    {
    }

    public function upload(UploadedFileInformation $file): string
    {
        if (!$file->fileInformation instanceof UploadedFile) {
            throw new \InvalidArgumentException(\sprintf('Expected an instance of <%s> : <%s> given', UploadedFile::class, \get_class($file->fileInformation)));
        }

        $file->fileInformation->file->move(
            \sprintf('%s%s', $this->publicDirectory, self::UPLOADED_PROFILE_PICTURES_DIRECTORY),
            $file->fileInformation->file->getClientOriginalName()
        );

        return \sprintf('%s/%s', self::UPLOADED_PROFILE_PICTURES_DIRECTORY, $file->fileInformation->file->getClientOriginalName());
    }
}
