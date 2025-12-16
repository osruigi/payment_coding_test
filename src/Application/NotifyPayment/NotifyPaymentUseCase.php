<?php

declare(strict_types=1);

namespace App\Application\NotifyPayment;

use App\Domain\Payment\Payment;

final class NotifyPaymentUseCase
{
    public function __construct(
        private readonly NotificationSenderPort $notificationSender,
        private readonly SignatureGeneratorPort $signatureGenerator
    ) {
    }

    public function execute(Payment $payment): void
    {
        $signature = $this->signatureGenerator->generate($payment);

        $this->notificationSender->send($payment, $signature);
    }
}
