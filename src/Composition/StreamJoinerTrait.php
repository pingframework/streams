<?php

namespace Pingframework\Streams\Composition;

trait StreamJoinerTrait
{
    use StreamElementsTrait;

    /**
     * Returns string representation of elements using given separator.
     * When array is empty, the result is empty string.
     *
     * @param string $separator Join separator.
     *
     * @return string
     */
    public function join(string $separator = ''): string
    {
        return implode($separator, $this->elements);
    }
}