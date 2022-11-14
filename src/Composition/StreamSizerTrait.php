<?php

namespace Pingframework\Streams\Composition;

trait StreamSizerTrait
{
    use StreamElementsTrait;

    /**
     * Size of the array.
     *
     * @return int
     */
    public function size(): int
    {
        return count($this->elements);
    }
}