<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Infrastructure\Serialization\PaymentSerializer;
use App\Application\NotifyPayment\NotificationSenderPort;
use App\Domain\Payment\Payment;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

final class HttpNotificationSender implements NotificationSenderPort
{
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly string $endpoint,
        private readonly PaymentSerializer $serializer
    ) {
    }

    public function send(Payment $payment, string $signature): void
    {
        try {
            $this->httpClient->request('POST', $this->endpoint, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Signature' => $signature,
                ],
                'json' => $this->serializer->toArray($payment),
            ]);
        } catch (GuzzleException $exception) {
            throw new \RuntimeException(
                'Failed to send payment notification',
                previous: $exception
            );
        }
    }
}
