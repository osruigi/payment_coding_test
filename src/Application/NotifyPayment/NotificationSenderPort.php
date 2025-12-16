<?php

declare(strict_types=1);

namespace App\Application\NotifyPayment;

use App\Domain\Payment\Payment;

interface NotificationSenderPort
{
    /**
     * Envía la notificación de un pago ya firmada.
     *
     * @throws \RuntimeException en caso de error de envío
     */
    public function send(Payment $payment, string $signature): void;
}
