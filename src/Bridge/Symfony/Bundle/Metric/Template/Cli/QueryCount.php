<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Cli;

use Shippeo\Heimdall\Domain\Metric\Tag;
use Shippeo\Heimdall\Domain\Metric\Template\Counter;

final class QueryCount implements Counter
{
    /** @var int */
    private $count;

    public function __construct(int $count)
    {
        $this->count = $count;
    }

    public function value(): int
    {
        return $this->count;
    }

    public function name(): string
    {
        return 'cli.database.query_count';
    }

    public function tags(): Tag\NameIterator
    {
        return new Tag\NameIterator(
            [
                new Tag\Name('command'),
                new Tag\Name('exit_code'),
            ]
        );
    }
}
