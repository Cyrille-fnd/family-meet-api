<?php

declare(strict_types=1);

namespace App\Infrastructure\Bridge\Symfony\Component\HttpFoundation\File;

use App\Domain\ValueObject\FileInformationInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

final readonly class UploadedFile implements FileInformationInterface
{
    public function __construct(
        public SymfonyUploadedFile $file
    ) {
    }

    public static function fromSymfonyUploadedFile(SymfonyUploadedFile $file): self
    {
        return new self($file);
    }
}
