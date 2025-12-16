<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Application\NotifyPayment\NotifyPaymentUseCase;
use App\Domain\Payment\NotificationId;
use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentStatus;
use App\Infrastructure\Security\JwtSignatureGenerator;
use App\Infrastructure\Serialization\PaymentSerializer;
use PHPUnit\Framework\TestCase;

final class NotifyPaymentFlowTest extends TestCase
{
    public function test_it_sends_a_signed_payment_notification(): void
    {
        $sender = new SpyNotificationSender();
        $serializer = new PaymentSerializer();
        $signatureGenerator = new JwtSignatureGenerator('integration-secret', $serializer);

        $useCase = new NotifyPaymentUseCase(
            notificationSender: $sender,
            signatureGenerator: $signatureGenerator
        );

        $payment = new Payment(
            amount: 75.0,
            status: PaymentStatus::PENDING,
            creditorAccount: 'ESAAA',
            debtorAccount: 'ESBBB',
            notificationId: NotificationId::fromString(
                '123e4567-e89b-12d3-a456-426614174000'
            )
        );

        $useCase->execute($payment);

        self::assertTrue($sender->called);
        self::assertSame($payment, $sender->payment);
        self::assertNotEmpty($sender->signature);
    }
}
