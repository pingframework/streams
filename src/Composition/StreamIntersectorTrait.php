<?php

namespace Pingframework\Streams\Composition;

trait StreamIntersectorTrait
{
    use StreamElementsTrait;
    use StreamBuilderTrait;

    /**
     * Keeps elements that exists also in given array.
     *
     * @param iterable $iterable
     *
     * @return static
     */
    public function intersect(iterable $iterable): static
    {
        $this->elements = array_intersect($this->elements, self::of($iterable)->toMap());
        return $this;
    }
}