<?php

namespace Pingframework\Streams\Composition;

trait StreamUniquerTrait
{
    use StreamElementsTrait;

    /**
     * Removes duplicates from array
     * @link https://php.net/manual/en/function.array-unique.php
     *
     * @param int $flags The optional second parameter flags may be used to modify the sorting behavior using these values:
     *                   SORT_REGULAR - compare items normally (don't change types)
     *                   SORT_NUMERIC - compare items numerically
     *                   SORT_STRING - compare items as strings
     *                   SORT_LOCALE_STRING - compare items as strings, based on the current locale.
     *
     * @return static
     */
    public function unique(int $flags = SORT_REGULAR): static
    {
        $this->elements = array_unique($this->elements, $flags);
        return $this;
    }
}