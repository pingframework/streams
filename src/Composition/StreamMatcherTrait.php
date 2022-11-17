<?php

namespace Pingframework\Streams\Composition;

use InvalidArgumentException;
use PhpOption\None;
use PhpOption\Option;
use Throwable;

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
     * Checks if all elements of the stream match given predicate or throws passed exception.
     *
     * @template T
     *
     * @param callable              $predicate   Predicate to test each element.
     * @param string|Throwable|null $description Error message or exception to throw if the predicate is not satisfied.
     *                                           If null is passed, InvalidArgumentException is thrown.
     *
     * @return static
     */
    public function allMatchOrThrow(callable $predicate, string|null|Throwable $description = null): static
    {
        if ($description === null) {
            $description = new InvalidArgumentException('Not all elements match given predicate.');
        }

        assert($this->allMatch($predicate), $description);
        return $this;
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