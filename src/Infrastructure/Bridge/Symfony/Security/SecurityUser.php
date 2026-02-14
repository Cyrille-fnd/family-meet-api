<?php

declare(strict_types=1);

namespace App\Infrastructure\Bridge\Symfony\Security;

use App\Domain\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(private User $user)
    {
    }

    public function getPassword(): string
    {
        return $this->user->getPassword();
    }

    public function getRoles(): array
    {
        return $this->user->getRoles();
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        \assert('' !== $this->user->getEmail());

        return $this->user->getEmail();
    }
}
