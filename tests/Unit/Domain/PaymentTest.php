<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Payment\NotificationId;
use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentStatus;
use PHPUnit\Framework\TestCase;

final class PaymentTest extends TestCase
{
    public function test_it_creates_a_valid_payment(): void
    {
        $payment = new Payment(
            amount: 100.0,
            status: PaymentStatus::COMPLETED,
            creditorAccount: 'ES123',
            debtorAccount: 'ES456',
            notificationId: NotificationId::fromString(
                '123e4567-e89b-12d3-a456-426614174000'
            )
        );

        self::assertSame(100.0, $payment->amount());
        self::assertSame(PaymentStatus::COMPLETED, $payment->status());
    }

    public function test_it_rejects_invalid_amount(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Payment(
            amount: 0,
            status: PaymentStatus::PENDING,
            creditorAccount: 'ES123',
            debtorAccount: 'ES456',
            notificationId: NotificationId::fromString(
                '123e4567-e89b-12d3-a456-426614174000'
            )
        );
    }
}
