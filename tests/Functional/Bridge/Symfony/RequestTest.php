<?php

declare(strict_types=1);

namespace Functional\Bridge\Symfony;

/**
 * @internal
 * @coversNothing
 */
final class RequestTest extends WebTestCase
{
    /**
     * @todo add real test solution
     */
    public function testRequest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        static::assertSame(200, $client->getResponse()->getStatusCode());
    }
}
