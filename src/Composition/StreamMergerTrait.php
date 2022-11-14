<?php

namespace Pingframework\Streams\Composition;

trait StreamMergerTrait
{
    use StreamElementsTrait;
    use StreamBuilderTrait;

    /**
     * Merges given "iterable" to current elements.
     *
     * @param iterable $iterable
     *
     * @return static
     */
    public function merge(iterable $iterable): static
    {
        $this->elements = array_merge($this->elements, self::of($iterable)->toMap());

        return $this;
    }

    /**
     * Merge recursively given "iterable" to current elements.
     *
     * @param iterable $iterable
     *
     * @return static
     */
    public function mergeRecursive(iterable $iterable): static
    {
        $this->elements = array_merge_recursive($this->elements, self::of($iterable)->toMap());

        return $this;
    }
}