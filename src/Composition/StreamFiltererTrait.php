<?php

namespace Pingframework\Streams\Composition;

trait StreamFiltererTrait
{
    use StreamElementsTrait;

    /**
     * Removes elements that do not match given predicate
     *
     * @param callable $predicate Function that evaluates element to boolean value
     *
     * @return static
     */
    public function filter(callable $predicate): static
    {
        $this->elements = array_filter($this->elements, $predicate, ARRAY_FILTER_USE_BOTH);
        return $this;
    }
}