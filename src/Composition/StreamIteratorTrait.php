<?php

namespace Pingframework\Streams\Composition;

trait StreamIteratorTrait
{
    use StreamElementsTrait;

    /**
     * Performs given function on each element.
     *
     * @param callable $func Function to perform on each element.
     *
     * @return static
     */
    public function forEach(callable $func): static
    {
        array_walk($this->elements, $func);
        return $this;
    }

    /**
     * Performs given function on each element recursively.
     *
     * @param callable $func Function to perform on each element.
     *
     * @return static
     */
    public function forEachRecursive(callable $func): static
    {
        array_walk_recursive($this->elements, $func);
        return $this;
    }

    /**
     * Verify that the contents of a variable is accepted by the iterable pseudo-type,
     * i.e. that it is an array or an object implementing Traversable.
     * @link https://php.net/manual/en/function.is-iterable.php
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isIterable(mixed $value): bool
    {
        return is_iterable($value);
    }
}