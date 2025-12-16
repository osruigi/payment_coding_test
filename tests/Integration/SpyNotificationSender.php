<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Application\NotifyPayment\NotificationSenderPort;
use App\Domain\Payment\Payment;

final class SpyNotificationSender implements NotificationSenderPort
{
    public bool $called = false;
    public ?Payment $payment = null;
    public ?string $signature = null;

    public function send(Payment $payment, string $signature): void
    {
        $this->called = true;
        $this->payment = $payment;
        $this->signature = $signature;
    }
}
