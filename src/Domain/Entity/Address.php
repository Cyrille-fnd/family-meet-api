<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Identity\AddressId;

class Address
{
    public function __construct(
        private AddressId $id,
        private string $street,
        private string $zipCode,
        private string $city,
        private string $country,
    ) {
    }

    public function id(): AddressId
    {
        return $this->id;
    }

    public function formattedAddress(): string
    {
        return \sprintf(
            '%s, %s, %s, %s',
            $this->street,
            $this->zipCode,
            $this->city,
            $this->country
        );
    }
}
