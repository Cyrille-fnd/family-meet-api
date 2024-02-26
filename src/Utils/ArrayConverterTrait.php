<?php

namespace App\Utils;

trait ArrayConverterTrait
{
    /**
     * @param array<string, \JsonSerializable> $data
     *
     * @return array<string, mixed>
     */
    public static function toArray(array $data): array
    {
        return array_map(function (\JsonSerializable $object) {
            return $object->jsonSerialize();
        }, $data);
    }
}
