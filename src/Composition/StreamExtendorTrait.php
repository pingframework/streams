<?php

namespace Pingframework\Streams\Composition;

trait StreamExtendorTrait
{
    use StreamElementsTrait;

    /**
     * Adds element to end of internal array.
     *
     * @param mixed $element Element to add.
     *
     * @return static
     */
    public function append(mixed $element): static
    {
        $this->elements[] = $element;
        return $this;
    }

    /**
     * Adds element to start of internal array.
     *
     * @param mixed $element Element to add.
     *
     * @return static
     */
    public function prepend(mixed $element): static
    {
        array_unshift($this->elements, $element);
        return $this;
    }
}