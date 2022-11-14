<?php

namespace Pingframework\Streams\Composition;

trait StreamMapperTrait
{
    use StreamElementsTrait;

    /**
     * Returns a stream consisting of the results of applying the given function to the elements of this stream.
     *
     * Element value will be provided as first argument of $func, element index will be provided as second argument, so
     * if you want to use functions with more than one argument as a map function (for example strtolower), you should
     * use call::func('strtolower') to suppress second argument that have would be an index.
     *
     * @param callable $func Function that should transform an argument
     *
     * @return static
     *
     * @see flatMap
     */
    public function map(callable $func): static
    {
        foreach ($this->elements as $index => &$value) {
            $value = call_user_func($func, $value, $index);
        }

        reset($this->elements);
        return $this;
    }
}