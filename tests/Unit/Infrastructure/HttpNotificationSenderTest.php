<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure;

use App\Domain\Payment\NotificationId;
use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentStatus;
use App\Infrastructure\Http\HttpNotificationSender;
use App\Infrastructure\Serialization\PaymentSerializer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class HttpNotificationSenderTest extends TestCase
{
    public function test_it_sends_json_with_signature_header(): void
    {
        $history = [];
        $mock = new MockHandler([new Response(200)]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push(Middleware::history($history));

        $client = new Client(['handler' => $handlerStack]);
        $serializer = new PaymentSerializer();
        $sender = new HttpNotificationSender($client, 'https://example.com/notify', $serializer);

        $payment = new Payment(
            amount: 33.5,
            status: PaymentStatus::COMPLETED,
            creditorAccount: 'CREDITOR',
            debtorAccount: 'DEBTOR',
            notificationId: NotificationId::generate()
        );

        $sender->send($payment, 'signed-token');

        self::assertCount(1, $history);
        $request = $history[0]['request'];

        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://example.com/notify', (string) $request->getUri());
        self::assertSame(['signed-token'], $request->getHeader('Signature'));

        $body = json_decode((string) $request->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame($serializer->toArray($payment), $body);
    }

    public function test_it_throws_on_http_error(): void
    {
        $mock = new MockHandler([new Response(500)]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $serializer = new PaymentSerializer();
        $sender = new HttpNotificationSender($client, 'https://example.com/notify', $serializer);

        $payment = new Payment(
            amount: 10.0,
            status: PaymentStatus::FAILED,
            creditorAccount: 'CREDITOR',
            debtorAccount: 'DEBTOR',
            notificationId: NotificationId::generate()
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to send payment notification');

        $sender->send($payment, 'signed-token');
    }
}
