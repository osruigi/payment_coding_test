<?php

declare(strict_types=1);

namespace App\Application\NotifyPayment;

use App\Domain\Payment\Payment;

interface SignatureValidatorPort
{
    /**
     * Valida que la firma coincida exactamente con los datos del Payment.
     */
    public function isValid(Payment $payment, string $signature): bool;
}
