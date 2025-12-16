<?php

declare(strict_types=1);

namespace App\Infrastructure\Serialization;

use App\Domain\Payment\Payment;

final class PaymentSerializer
{
    public function toArray(Payment $payment): array
    {
        return [
            'amount' => $payment->amount(),
            'status' => $payment->status()->value,
            'creditor_account' => $payment->creditorAccount(),
            'debtor_account' => $payment->debtorAccount(),
            'notification_id' => $payment->notificationId()->value(),
        ];
    }
}
