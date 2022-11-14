<?php

namespace Pingframework\Streams\Composition;

trait StreamKeyValueTrait
{
    use StreamElementsTrait;

    /**
     * Transform elements to list with all the keys of internal array.
     *
     * If a search_value is specified, then only the keys for that value are taken.
     * Otherwise, all the keys from the internal array are taken.
     *
     * @link https://php.net/manual/en/function.array-keys.php
     *
     * @param mixed $searchValue If specified, then only keys containing this value are taken.
     * @param bool  $strict      Determines if strict comparison (===) should be used during the search.
     *
     * @return static
     */
    public function keys(mixed $searchValue = null, bool $strict = false): static
    {
        if ($searchValue !== null) {
            $this->elements = array_keys($this->elements, $searchValue, $strict);
        } else {
            $this->elements = array_keys($this->elements);
        }

        return $this;
    }

    /**
     * Transform elements to list with all the values of internal array.
     *
     * @return static
     */
    public function values(): static
    {
        $this->elements = array_values($this->elements);
        return $this;
    }
}