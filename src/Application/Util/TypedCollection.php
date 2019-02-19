<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Util;

use Webmozart\Assert\Assert;

abstract class TypedCollection implements \ArrayAccess, \IteratorAggregate
{
    /** @var array */
    protected $elements;

    /** @var string */
    private $type;

    public function __construct(string $type, array $elements)
    {
        $this->type = $type;

        Assert::allIsInstanceOf($elements, $this->type);
        $this->elements = $elements;
    }

    public function mergeWith(self $collection): self
    {
        return new static(
            \array_merge(
                $this->toArray(),
                $collection->toArray()
            )
        );
    }

    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->elements[$offset]);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->elements[$offset];
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        Assert::isInstanceOf($value, $this->type);

        if ($offset === null) {
            $this->elements[] = $value;
        } else {
            $this->elements[$offset] = $value;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->elements[$offset]);
    }
}
