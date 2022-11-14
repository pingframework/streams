<?php

namespace Pingframework\Streams\Composition;

trait StreamCalculatorTrait
{
    use StreamElementsTrait;
    use StreamComparatorTrait;

    /**
     * Returns the sum of all elements.
     * Returns 0 if stream is empty.
     *
     * @return int|float
     */
    public function sum(): int|float
    {
        return array_sum($this->elements);
    }

    /**
     * Returns the sum of all elements by provided $valFunc.
     * Returns 0 if stream is empty.
     *
     * Example:
     * [code]
     *      Stream::of([
     *              ['name' => 'John', 'age' => 33],
     *              ['name' => 'Jolka', 'age' => 21],
     *          ])
     *          ->sumBy(get::value('age'));
     *     // result: 54
     * [/code]
     *
     * @param callable $valFunc Function to get value from element.
     *
     * @return int|float
     */
    public function sumBy(callable $valFunc): int|float
    {
        return array_sum(array_map($valFunc, $this->elements));
    }
}