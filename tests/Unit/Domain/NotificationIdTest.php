<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Payment\NotificationId;
use PHPUnit\Framework\TestCase;

final class NotificationIdTest extends TestCase
{
    public function test_it_generates_a_valid_uuid(): void
    {
        $id = NotificationId::generate();

        self::assertMatchesRegularExpression(
            '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/',
            $id->value()
        );
    }
}
