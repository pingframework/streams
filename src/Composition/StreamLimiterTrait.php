<?php

namespace Pingframework\Streams\Composition;

trait StreamLimiterTrait
{
    use StreamElementsTrait;
    use StreamSlicerTrait;

    /**
     * Limits number of element to given size
     *
     * @param int $n
     *
     * @return static
     */
    public function limit(int $n): static
    {
        return $this->slice(0, $n);
    }
}