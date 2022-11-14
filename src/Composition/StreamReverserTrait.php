<?php

namespace Pingframework\Streams\Composition;

trait StreamReverserTrait
{
    use StreamElementsTrait;

    /**
     * Reverses stream.
     *
     * @return static
     */
    public function reverse(): static
    {
        $this->elements = array_reverse($this->elements);
        return $this;
    }
}