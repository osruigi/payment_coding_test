<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure;

use App\Domain\Payment\NotificationId;
use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentStatus;
use App\Infrastructure\Security\JwtSignatureGenerator;
use App\Infrastructure\Serialization\PaymentSerializer;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PHPUnit\Framework\TestCase;

final class JwtSignatureGeneratorTest extends TestCase
{
    private const SECRET = 'test-secret';

    public function test_it_generates_a_valid_jwt_signature(): void
    {
        $payment = new Payment(
            amount: 50.0,
            status: PaymentStatus::COMPLETED,
            creditorAccount: 'ES111',
            debtorAccount: 'ES222',
            notificationId: NotificationId::fromString(
                '123e4567-e89b-12d3-a456-426614174000'
            )
        );

        $serializer = new PaymentSerializer();

        $generator = new JwtSignatureGenerator(self::SECRET, $serializer);

        $token = $generator->generate($payment);

        $decoded = JWT::decode($token, new Key(self::SECRET, 'HS256'));

        self::assertObjectHasProperty('payment', $decoded);
        self::assertIsNumeric($decoded->payment->amount);
        self::assertEquals(50.0, $decoded->payment->amount);
        self::assertSame('completed', $decoded->payment->status);
    }
}
