<?php

namespace Pingframework\Streams\Composition;

trait StreamSkipperTrait
{
    use StreamElementsTrait;

    /**
     * Skips first N elements.
     *
     * @param int $n
     *
     * @return static
     */
    public function skip(int $n): static
    {
        $this->elements = array_slice($this->elements, $n, preserve_keys: true);
        return $this;
    }
}