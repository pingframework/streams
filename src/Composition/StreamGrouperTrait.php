<?php

namespace Pingframework\Streams\Composition;

trait StreamGrouperTrait
{
    use StreamElementsTrait;

    /**
     * Groups elements by value returned by given function
     *
     * Example:
     * <code>
     *     Stream::of([1, 2, 3, 4])
     *          ->group(fn(int $number): bool => $number % 2 === 0)
     *          ->toMap();
     *
     *     // result: [0 => [2, 4], 1 => [1, 3]]
     * </code>
     *
     * @param callable $func Function generating grouping key for each group of elements
     *
     * @return static
     *
     * @see partition
     */
    public function group(callable $func): static
    {
        $elements = array();

        foreach($this->elements as $index => $value) {
            $key = call_user_func($func, $value, $index);
            $elements[$key][] = $value;
        }

        $this->elements = $elements;

        return $this;
    }
}