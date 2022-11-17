<?php

/**
 * Ping Streams
 *
 * MIT License
 *
 * Copyright (c) 2022 pingframework
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */

declare(strict_types=1);


namespace Pingframework\Streams\Interfaces;

use PhpOption\Option;
use Throwable;

/**
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */
interface StreamMatchable
{
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
    public function allMatch(callable $predicate): bool;

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
    public function allMatchOrThrow(callable $predicate, string|null|Throwable $description): static;

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
    public function anyMatch(callable $predicate): bool;

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
    public function noneMatch(callable $predicate): bool;

    /**
     * Gets {@link Option} containing first element that match given predicate.
     *
     * @param callable $predicate
     *
     * @return Option
     */
    public function firstMatch(callable $predicate): Option;
}