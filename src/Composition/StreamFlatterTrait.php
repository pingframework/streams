<?php

namespace Pingframework\Streams\Composition;

use LogicException;

trait StreamFlatterTrait
{
    use StreamElementsTrait;
    use StreamIteratorTrait;
    use StreamMapperTrait;

    /**
     * Transforms each element to array values using given function and merges all result arrays into one array
     *
     * Example:
     * <code>
     *     Stream::of(['some', 'words'])
     *          ->flatMap(call::func('str_split'))
     *          ->toList();
     *
     *     // result: ['s', 'o', 'm', 'e', 'w', 'o', 'r', 'd', 's']
     * </code>
     *
     * @param callable $func Function that should transform an argument to array.
     *
     * @return static
     *
     * @see map
     */
    public function flatMap(callable $func): static
    {
        return $this->map($func)->flat();
    }

    /**
     * Merges each elements into one array. Each element should be an array or Traversable.
     *
     * Example:
     * <code>
     *     Stream::of([
     *              ['value1', 'value2'],
     *              ['value3', 'value4'],
     *          ])
     *          ->flat()
     *          ->toList();
     *
     *     // result: ['value1', 'value2', 'value3', 'value4']
     * </code>
     *
     * @return static
     *
     * @throws LogicException When at least one element is not array or Traversable
     */
    public function flat(): static
    {
        $elements = array();

        foreach($this->elements as $values) {
            assert($this->isIterable($values), new LogicException('All elements should be iterable'));

            foreach($values as $value) {
                $elements[] = $value;
            }
        }

        $this->elements = $elements;

        return $this;
    }
}