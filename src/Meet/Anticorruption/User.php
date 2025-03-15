<?php

declare(strict_types=1);

namespace App\Meet\Anticorruption;

use App\Entity\User as LegacyUser;
use App\Meet\Domain\ValueObject\Identity\UserId;
use App\Meet\Domain\ValueObject\SignupInformation;

class User
{
    public function __construct(
        public UserId $id,
        public LegacyUser $legacyUser,
    ) {
    }

    public static function fromUserInformation(SignupInformation $userInformation): self
    {
        $legacyUser = LegacyUser::create(
            id: $userInformation->id->value(),
            email: $userInformation->email,
            password: $userInformation->password,
            sex: $userInformation->sex,
            firstname: $userInformation->firstName,
            lastname: $userInformation->lastName,
            bio: $userInformation->bio,
            birthday: new \DateTime($userInformation->birthday->format('Y-m-d')),
            city: $userInformation->city,
            pictureUrl: null,
        );

        return new self(
            id: $userInformation->id,
            legacyUser: $legacyUser
        );
    }

    public static function fromLegacyUser(LegacyUser $legacyUser): self
    {
        return new self(
            id: UserId::fromString($legacyUser->getId()),
            legacyUser: $legacyUser,
        );
    }
}
