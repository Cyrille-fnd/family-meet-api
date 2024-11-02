<?php

declare(strict_types=1);

namespace App\Meet\Anticorruption;

use App\Entity\User as LegacyUser;
use App\Meet\Domain\ValueObject\SignupInformation;
use App\Meet\Domain\ValueObject\UserId;
use Symfony\Component\Uid\Uuid;

class User
{
    public function __construct(
        public UserId $id,
        public LegacyUser $legacyUser,
    ) {
    }

    public static function fromUserInformation(SignupInformation $userInformation): self
    {
        $legacyUser = new LegacyUser();
        $legacyUser
            ->setId(Uuid::fromString($userInformation->id->value()))
            ->setEmail($userInformation->email)
            ->setPassword($userInformation->password)
            ->setRoles(['ROLE_USER'])
            ->setSex($userInformation->sex)
            ->setFirstname($userInformation->firstName)
            ->setLastname($userInformation->lastName)
            ->setBio($userInformation->bio)
            ->setBirthday(new \DateTime($userInformation->birthday->format('Y-m-d')))
            ->setCity($userInformation->city)
            ->setPictureUrl(null)
            ->setCreatedAt(new \DateTime());

        return new self(
            id: $userInformation->id,
            legacyUser: $legacyUser
        );
    }

    public static function fromLegacyUser(LegacyUser $legacyUser): self
    {
        if (null === $legacyUser->getId()) {
            throw new \RuntimeException('Legacy user ID cannot be null');
        }

        return new self(
            id: UserId::fromString($legacyUser->getId()->toRfc4122()),
            legacyUser: $legacyUser,
        );
    }
}
