<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Infrastructure\Database;

use League\StatsD\Client as LeagueClient;
use League\StatsD\Exception\ConnectionException;
use Shippeo\Heimdall\Application\Database\StatsD\Client;
use Shippeo\Heimdall\Application\Database\StatsD\Key;

final class StatsDClient implements Client
{
    /** @var LeagueClient */
    private $client;

    public function __construct(string $host, int $port)
    {
        $this->client = new LeagueClient();
        $this->client->configure(
            [
                'host' => $host,
                'port' => $port,
            ]
        );
    }

    public static function fromArray(array $configuration): self
    {
        return new self((string) $configuration['host'], (int) $configuration['port']);
    }

    public function increment(Key $key, int $value): void
    {
        try {
            $this->client->increment((string) $key, $value);
        } catch (ConnectionException $exception) {
        }
    }

    public function timing(Key $key, float $time): void
    {
        try {
            $this->client->timing((string) $key, $time);
        } catch (ConnectionException $exception) {
        }
    }

    public function gauge(Key $key, float $value): void
    {
        try {
            $this->client->gauge((string) $key, $value);
        } catch (ConnectionException $exception) {
        }
    }
}
