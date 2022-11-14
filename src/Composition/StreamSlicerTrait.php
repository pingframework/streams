<?php

namespace Pingframework\Streams\Composition;

trait StreamSlicerTrait
{
    use StreamElementsTrait;

    /**
     * Slices stream.
     * @link https://php.net/manual/en/function.array-slice.php
     *
     * @param int      $offset If offset is non-negative, the sequence will start at that offset in the array.
     *                         If offset is negative, the sequence will start that far from the end of the array.
     * @param int|null $length [optional] If length is given and is positive,
     *                         then the sequence will have that many elements in it.
     *                         If length is given and is negative then the sequence will stop that many elements
     *                         from the end of the array. If it is omitted, then the sequence will have everything
     *                         from offset up until the end of the array.
     *
     * @return static
     */
    public function slice(int $offset, ?int $length = null): static
    {
        $this->elements = array_slice($this->elements, $offset, $length, true);
        return $this;
    }
}