<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Database;

final class DatabaseIterator implements \Iterator
{
    /** @var Database[] */
    private $databases = [];

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
     * {@inheritdoc}
     */
    public function current(): Database
    {
        return \current($this->databases);
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        \next($this->databases);
    }

    /**
     * {@inheritdoc}
     *
     * @return null|int|string
     */
    public function key()
    {
        return \key($this->databases);
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return \current($this->databases) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        \reset($this->databases);
    }
}
