<?php

namespace Pingframework\Streams\Composition;

trait StreamPartitionTrait
{
    use StreamElementsTrait;

    /**
     * Splits elements into two groups using given predicate.
     * Elements that match predicate are stored under "0" index,
     * other elements under "1" index.
     *
     * Example:
     * <code>
     *      [$males, $females] = Stream::of($persons)
     *          ->partition(fn($person) => $person->getSex() === 'male')
     *          ->toList();
     * </code>
     *
     * @param callable $predicate Function that evaluates element to boolean value
     *
     * @return static
     *
     * @see groupBy
     */
    public function partition(callable $predicate): static
    {
        $result = [[], []];

        foreach ($this->elements as $index => $value) {
            $result[call_user_func($predicate, $value, $index) ? 0 : 1][] = $value;
        }

        $this->elements = $result;

        return $this;
    }
}