<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Database;

/**
 * @implements \IteratorAggregate<int, Database>
 */
final class DatabaseIterator implements \IteratorAggregate
{
    /** @var Database[] */
    private $databases = [];

    /** @param iterable<Database> $databases */
    public function __construct(iterable $databases)
    {
        foreach ($databases as $database) {
            if (!$database instanceof Database) {
                throw new \InvalidArgumentException('Expect instance of '.Database::class);
            }

            $this->databases[] = $database;
        }
    }

    /**
     * @return \Generator<int, Database>
     */
    public function getIterator(): \Generator
    {
        yield from $this->databases;
    }
}
