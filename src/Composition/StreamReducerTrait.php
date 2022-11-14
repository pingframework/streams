<?php

namespace Pingframework\Streams\Composition;

use PhpOption\None;
use PhpOption\Option;

trait StreamReducerTrait
{
    use StreamElementsTrait;

    /**
     * Iteratively reduce the stream to a single value using a $binaryOperator function.
     *
     * @param callable $binaryOperator  Function that has two arguments and returns result of operation on two arguments
     *                                  callback(mixed $carry, mixed $item): mixed
     *                                  carry - Holds the return value of the previous iteration;
     *                                  in the case of the first iteration it instead holds the value of initial.
     *                                  item - Holds the value of the current iteration.
     *
     * @param mixed    $initial         If the optional initial is available,
     *                                  it will be used at the beginning of the process,
     *                                  or as a final result in case the array is empty.
     *
     * @return Option Returns {@see Some} containing the result of the reduction if the array is not empty, otherwise {@see None}.
     */
    public function reduce(callable $binaryOperator, mixed $initial = null): Option
    {
        if (!$this->elements && $initial === null) {
            return None::create();
        }

        return Option::fromValue(array_reduce($this->elements, $binaryOperator, $initial));
    }
}