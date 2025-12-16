<?php

declare(strict_types=1);

namespace App\Application\NotifyPayment;

use App\Domain\Payment\Payment;

interface SignatureGeneratorPort
{
    /**
     * Genera una firma para los datos del pago.
     */
    public function generate(Payment $payment): string;
}
