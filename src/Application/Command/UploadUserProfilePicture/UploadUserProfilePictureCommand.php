<?php

declare(strict_types=1);

namespace App\Application\Command\UploadUserProfilePicture;

use App\Application\CommandInterface;
use App\Domain\ValueObject\Identity\UserId;
use App\Domain\ValueObject\UploadedFileInformation;

final readonly class UploadUserProfilePictureCommand implements CommandInterface
{
    public function __construct(
        public UserId $id,
        public UploadedFileInformation $file,
    ) {
    }
}
