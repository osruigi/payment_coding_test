<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Infrastructure\Serialization\PaymentSerializer;
use App\Application\NotifyPayment\SignatureGeneratorPort;
use App\Domain\Payment\Payment;
use Firebase\JWT\JWT;

final class JwtSignatureGenerator implements SignatureGeneratorPort
{
    public function __construct(
        private readonly string $secret,
        private readonly PaymentSerializer $serializer
    ) {
    }

    public function generate(Payment $payment): string
    {
        $payload = [
            'payment' => $this->serializer->toArray($payment),
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }
}
