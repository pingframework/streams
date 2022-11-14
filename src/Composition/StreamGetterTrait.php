<?php

namespace Pingframework\Streams\Composition;

use PhpOption\Option;

trait StreamGetterTrait
{
    use StreamElementsTrait;

    /**
     * Gets {@link Option) containing element stored in given index.
     * When index does not exist or value is strictly equal to null, {@link None} is returned, otherwise {@link Some}
     *
     * @param mixed $index
     *
     * @return Option
     */
    public function get(mixed $index): Option
    {
        return Option::fromValue($this->elements[$index] ?? null);
    }

    /**
     * Gets {@link Option} containing first element of the stream.
     * When stream is empty, {@link None} is returned, otherwise {@link Some}
     *
     * @return Option
     */
    public function first(): Option
    {
        return Option::fromValue($this->elements[array_key_first($this->elements)] ?? null);
    }

    /**
     * Gets {@link Option) containing last element in the stream.
     * When stream is empty, {@link None} is returned, otherwise {@link Some}
     *
     * @return Option
     *
     * @see first
     */
    public function last(): Option
    {
        return Option::fromValue($this->elements[array_key_last($this->elements)] ?? null);
    }
}