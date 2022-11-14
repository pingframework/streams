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

use LogicException;

/**
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */
interface StreamFlatable extends StreamMappable
{
    /**
     * Transforms each element to array values using given function and merges all result arrays into one array
     *
     * Example:
     * <code>
     *     Stream::of(['some', 'words'])
     *          ->flatMap(call::func('str_split'))
     *          ->toList();
     *
     *     //result:
     *     array('s', 'o', 'm', 'e', 'w', 'o', 'r', 'd', 's')
     * </code>
     *
     * @param callable $func Function that should transform an argument to array.
     *
     * @return static
     *
     * @see map
     */
    public function flatMap(callable $func): static;

    /**
     * Merges each elements into one array. Each element should be an array or Traversable.
     *
     * Example:
     * <code>
     *     Stream::of([
     *              ['value1', 'value2'],
     *              ['value3', 'value4'],
     *          ])
     *          ->flat()
     *          ->toList();
     *
     *     // result: ['value1', 'value2', 'value3', 'value4']
     * </code>
     *
     * @return static
     *
     * @throws LogicException When at least one element is not array or Traversable
     */
    public function flat(): static;
}