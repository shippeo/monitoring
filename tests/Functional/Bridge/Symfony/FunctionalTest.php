<?php

declare(strict_types=1);

namespace Functional\Bridge\Symfony;

use Shippeo\Heimdall\Application\AddMetric;

/**
 * @internal
 * @coversNothing
 */
final class FunctionalTest extends WebTestCase
{
    public function testConfiguration(): void
    {
        static::assertTrue($this->container()->has(AddMetric::class));
    }
}
