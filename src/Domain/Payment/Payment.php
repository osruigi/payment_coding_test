<?php

declare(strict_types=1);

namespace App\Domain\Payment;

final class Payment
{
    public function __construct(
        private readonly float $amount,
        private readonly PaymentStatus $status,
        private readonly string $creditorAccount,
        private readonly string $debtorAccount,
        private readonly NotificationId $notificationId
    ) {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function status(): PaymentStatus
    {
        return $this->status;
    }

    public function creditorAccount(): string
    {
        return $this->creditorAccount;
    }

    public function debtorAccount(): string
    {
        return $this->debtorAccount;
    }

    public function notificationId(): NotificationId
    {
        return $this->notificationId;
    }
}
