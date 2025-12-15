<?php

declare(strict_types=1);

namespace App\Domain\Payment;

final class NotificationId
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $value): self
    {
        // Validación mínima sin librerías externas
        if (!self::isValidUuid($value)) {
            throw new \InvalidArgumentException('Invalid UUID format');
        }

        return new self($value);
    }

    public function value(): string
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
