<?php

declare(strict_types=1);

namespace App\Domain\Payment;

final class NotificationId
{
    private function __construct(
        private readonly string $value
    ) {
    }

    public static function fromString(string $value): self
    {
        if (!self::isValidUuid($value)) {
            throw new \InvalidArgumentException('Invalid UUID format');
        }

        return new self($value);
    }

    /**
     * Genera un UUID v4 utilizando la librería ramsey/uuid.
     */
    public static function generate(): self
    {
        return self::fromString(\Ramsey\Uuid\Uuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private static function isValidUuid(string $uuid): bool
    {
        return (bool) preg_match(
            '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/',
            $uuid
        );
    }
}
