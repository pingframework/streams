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

namespace Pingframework\Streams\Helpers;

use Pingframework\Streams\Stream;

/**
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */
class func
{
    /**
     * @param callable $func
     * @return callable
     *
     * @deprecated Use {@link func#unary()} instead
     */
    public static function fix(callable $func): callable
    {
        return Functions::oneArgumentFunction($func);
    }

    /**
     * Wraps passed function and suppresses all arguments except first one.
     *
     * This function is very useful to make unary native functions with optional second argument. "map" family functions in
     * {@link Stream} passes to callback index of element as second argument, so if native function has second
     * argument with different meaning, index will be provided as second argument. To prevent that you have to make this
     * function unary.
     *
     * Example:
     *
     * <code>
     *  Stream::of(...)
     *      ->map(func::unary('count')) //count second argument is mode
     *      ->toList();
     * </code>
     *
     * @param callable $func
     * @return callable
     */
    public static function unary(callable $func): callable
    {
        return Functions::oneArgumentFunction($func);
    }
}