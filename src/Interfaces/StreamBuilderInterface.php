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
interface StreamBuilderInterface
{
    /**
     * Builds new stream.
     *
     * @param iterable $iterable Iterable to build stream from.
     *
     * @return StreamInterface
     */
    static public function of(iterable $iterable): StreamInterface;

    /**
     * Builds new stream from given object.
     *
     * @param object ...$object Object to build stream from.
     *
     * @return StreamInterface
     */
    static public function ofObject(object ...$object): StreamInterface;

    /**
     * Builds new stream from given string split by given separator.
     *
     * @param string $separator String separator.
     * @param string ...$string String to build stream from.
     *
     * @return StreamInterface
     */
    static public function ofString(string $separator = '', string ...$string): StreamInterface;

    /**
     * Builds new stream containing a range of elements.
     * @link https://php.net/manual/en/function.range.php
     *
     * @param string|int|float $start First value of the sequence.
     * @param string|int|float $end   The sequence is ended upon reaching the end value.
     * @param int|float        $step  If a step value is given, it will be used as the increment (or decrement)
     *                                between elements in the sequence.
     *                                Step must not equal 0 and must not exceed the specified range.
     *                                If not specified, step will default to 1.
     *
     * @return StreamInterface
     */
    static public function ofRange(
        string|int|float $start,
        string|int|float $end,
        int|float        $step = 1
    ): StreamInterface;
}