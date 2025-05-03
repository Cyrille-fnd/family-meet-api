<?php

declare(strict_types=1);

namespace App\Infrastructure\Bridge\Symfony\HttpKernel\Controller;

use App\Domain\ValueObject\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTargetedValueResolver('id')]
final class UuidValueResolver implements ValueResolverInterface
{
    /**
     * @return iterable<Uuid>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (null === $argumentType || !is_subclass_of($argumentType, Uuid::class)) {
            return [];
        }

        $value = $request->attributes->get($argument->getName());
        if (!\is_string($value)) {
            return [];
        }

        return [$argumentType::fromString($value)];
    }
}
