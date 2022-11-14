<?php

namespace Pingframework\Streams\Composition;

trait StreamDifferentiatorTrait
{
    use StreamElementsTrait;
    use StreamBuilderTrait;

    /**
     * Removes elements that exists also in given array
     *
     * @param iterable $iterable
     *
     * @return static
     */
    public function diff(iterable $iterable): static
    {
        $this->elements = array_diff($this->elements, self::of($iterable)->toMap());
        return $this;
    }
}