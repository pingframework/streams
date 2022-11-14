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

/**
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */
interface StreamSearchable
{
    /**
     * Gets {@link Option} containing max element by natural order or by given comparator
     *
     * @param callable|null $comparator Comparing function
     *
     * @return Option
     *
     * @see min
     */
    public function max(callable $comparator = null): Option;

    /**
     * Gets {@link Option} containing element with max value produced by provided $valFunc.
     *
     * Example:
     * [code]
     *      Stream::of([
     *              ['name' => 'John', 'age' => 33],
     *              ['name' => 'Jolka', 'age' => 21],
     *          ])
     *          ->maxBy(get::value('age'))
     *          // there is Option value
     *          ->getOrElse(['name' => 'Joe', 'age' => 54]);
     * [/code]
     *
     * @param callable $valFunc Value producing function.
     *
     * @return Option
     *
     * @see max
     * @see orderBy
     */
    public function maxBy(callable $valFunc): Option;

    /**
     * Gets {@link Option} containing min element by natural order or by given comparator.
     *
     * @param callable|null $comparator Comparing function.
     * @return Option
     */
    public function min(callable $comparator = null): Option;

    /**
     * Gets {@link Option} containing element with min value produced by provided $valFunc.
     *
     * Example:
     * [code]
     *      Stream::of([
     *              ['name' => 'John', 'age' => 33],
     *              ['name' => 'Jolka', 'age' => 21],
     *          ])
     *          ->minBy(get::value('age'))
     *          // there is Option value
     *          ->getOrElse(['name' => 'Joe', 'age' => 10]);
     * [/code]
     *
     * @param callable $valFunction Value producing function.
     *
     * @return Option
     */
    public function minBy(callable $valFunction): Option;
}