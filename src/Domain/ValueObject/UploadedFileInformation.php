<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final readonly class UploadedFileInformation
{
    public function __construct(
        public FileInformationInterface $fileInformation,
    ) {
    }
}
