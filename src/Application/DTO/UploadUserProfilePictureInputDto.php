<?php

declare(strict_types=1);

namespace App\Application\DTO;

final readonly class UploadUserProfilePictureInputDto
{
    public function __construct(
        public string $filename,
        public string $content,
    ) {
    }
}
