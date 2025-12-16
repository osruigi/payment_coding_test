<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Application\NotifyPayment\SignatureValidatorPort;
use App\Domain\Payment\Payment;
use App\Infrastructure\Serialization\PaymentSerializer;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class JwtSignatureValidator implements SignatureValidatorPort
{
    public function __construct(
        private readonly string $secret,
        private readonly PaymentSerializer $serializer
    ) {
    }

    public function isValid(Payment $payment, string $signature): bool
    {
        try {
            $decoded = JWT::decode($signature, new Key($this->secret, 'HS256'));
        } catch (\Throwable) {
            return false;
        }

        if (!isset($decoded->payment)) {
            return false;
        }

        return $this->serializer->toArray($payment) == $this->toArray($decoded->payment);
    }

    private function toArray(object $payment): array
    {
        return json_decode(
            json_encode($payment, flags: JSON_THROW_ON_ERROR),
            true,
            flags: JSON_THROW_ON_ERROR
        );
    }
}
