<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Util;

use Webmozart\Assert\Assert;

/**
 * @template E
 * @implements \ArrayAccess<int, E>
 * @implements \IteratorAggregate<int, E>
 */
abstract class TypedCollection implements \ArrayAccess, \IteratorAggregate
{
    /** @var array<int, E> */
    protected $elements;

    /** @var class-string<E> */
    private $type;

    /**
     * @param class-string<E> $type
     * @param array<int, E>   $elements
     */
    public function __construct(string $type, array $elements)
    {
        $this->type = $type;

        /**  @phpstan-ignore-next-line */
        Assert::allIsInstanceOf($elements, $this->type);
        $this->elements = $elements;
    }

    /** @return array<int, E> */
    public function toArray(): array
    {
        return $this->elements;
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
     * method to avoid issues with offsetExist() method in phpspecs but allows testing the real method
     *
     * @param mixed $offset
     */
    public function offsetExistsInCollection($offset): bool
    {
        return $this->offsetExists($offset);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->elements[$offset];
    }

    /**
     * {@inheritdoc}
     *
     * @param null|int $offset
     * @param E        $value
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value): void
    {
        /**  @phpstan-ignore-next-line */
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
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset): void
    {
        unset($this->elements[$offset]);
    }
}
