<?php

namespace Pingframework\Streams\Composition;

trait StreamRemapperTrait
{
    use StreamElementsTrait;

    /**
     * Re-indexes/Re-maps elements using provided function.
     * When indexes duplicate, last value will be used.
     *
     * @param callable $func
     *
     * @return static
     */
    public function remap(callable $func): static
    {
        $elements = array();

        foreach ($this->elements as $index => $value) {
            $newIndex = call_user_func($func, $value, $index);
            $elements[$newIndex] = $value;
        }

        $this->elements = $elements;

        return $this;
    }
}