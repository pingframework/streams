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

/**
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */
interface StreamIterable
{
    /**
     * Performs given function on each element.
     *
     * @param callable $func Function to perform on each element.
     *
     * @return static
     */
    public function forEach(callable $func): static;

    /**
     * Performs given function on each element recursively.
     *
     * @param callable $func Function to perform on each element.
     *
     * @return static
     */
    public function forEachRecursive(callable $func): static;

    /**
     * Verify that the contents of a variable is accepted by the iterable pseudo-type,
     * i.e. that it is an array or an object implementing Traversable.
     * @link https://php.net/manual/en/function.is-iterable.php
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isIterable(mixed $value): bool;
}