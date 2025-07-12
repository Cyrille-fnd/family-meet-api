<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\ValueObject\UploadedFileInformation;

interface FileUploaderInterface
{
    public function upload(UploadedFileInformation $file): string;
}
