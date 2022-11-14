<?php

namespace Pingframework\Streams\Composition;

use PhpOption\None;
use PhpOption\Option;

trait StreamMatcherTrait
{
    use StreamElementsTrait;

    /**
     * Returns true when all elements match given predicate.
     * When array is empty result is true.
     *
     * @param callable $predicate
     *
     * @return bool
     *
     * @see anyMatch
     * @see noneMatch
     */
    public function allMatch(callable $predicate): bool
    {
        foreach ($this->elements as $index => $value) {
            if (!call_user_func($predicate, $value, $index)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns true when at least one element matches given predicate.
     * When array is empty result is false.
     *
     * @param callable $predicate
     *
     * @return bool
     *
     * @see allMatch
     * @see noneMatch
     */
    public function anyMatch(callable $predicate): bool
    {
        foreach ($this->elements as $index => $value) {
            if (call_user_func($predicate, $value, $index)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true when all elements don match given predicate.
     * When array is empty result is true.
     *
     * @param callable $predicate
     * @return bool
     *
     * @see anyMatch
     * @see allMatch
     */
    public function noneMatch(callable $predicate): bool
    {
        foreach ($this->elements as $index => $value) {
            if (call_user_func($predicate, $value, $index)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets {@link Option} containing first element that match given predicate.
     *
     * @param callable $predicate
     *
     * @return Option
     */
    public function firstMatch(callable $predicate): Option
    {
        foreach ($this->elements as $index => $value) {
            if (call_user_func($predicate, $value, $index)) {
                return Option::fromValue($value);
            }
        }

        return None::create();
    }
}