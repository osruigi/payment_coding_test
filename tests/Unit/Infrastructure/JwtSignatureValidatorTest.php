<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure;

use App\Domain\Payment\NotificationId;
use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentStatus;
use App\Infrastructure\Security\JwtSignatureGenerator;
use App\Infrastructure\Security\JwtSignatureValidator;
use App\Infrastructure\Serialization\PaymentSerializer;
use PHPUnit\Framework\TestCase;

final class JwtSignatureValidatorTest extends TestCase
{
    private const SECRET = 'validator-secret';

    public function test_it_accepts_a_valid_signature(): void
    {
        $payment = new Payment(
            amount: 10.0,
            status: PaymentStatus::COMPLETED,
            creditorAccount: 'ESVAL',
            debtorAccount: 'ESVAL2',
            notificationId: NotificationId::generate()
        );

        $serializer = new PaymentSerializer();
        $generator = new JwtSignatureGenerator(self::SECRET, $serializer);
        $validator = new JwtSignatureValidator(self::SECRET, $serializer);

        $token = $generator->generate($payment);

        self::assertTrue($validator->isValid($payment, $token));
    }

    public function test_it_rejects_signature_with_wrong_secret(): void
    {
        $payment = new Payment(
            amount: 20.0,
            status: PaymentStatus::PENDING,
            creditorAccount: 'ESBAD',
            debtorAccount: 'ESBAD2',
            notificationId: NotificationId::generate()
        );

        $serializer = new PaymentSerializer();
        $generator = new JwtSignatureGenerator('other-secret', $serializer);
        $validator = new JwtSignatureValidator(self::SECRET, $serializer);

        $token = $generator->generate($payment);

        self::assertFalse($validator->isValid($payment, $token));
    }
}
