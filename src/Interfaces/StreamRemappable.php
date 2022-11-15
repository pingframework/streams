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
interface StreamRemappable
{
    /**
     * Replace stream's keys by given key-value pairs, where key is a key of stream and value is a new key.
     *
     * @param array $newKeysMap Key-value pairs, where key is a key of stream and value is a new key.
     * @param bool  $skipOnFail Suppress exception if key not found in stream.
     *
     * @return static
     */
    public function remap(array $newKeysMap, bool $skipOnFail = true): static;

    /**
     * Replace stream's keys by given key-value pairs, where key is a key of stream and value is a new key.
     * The difference from remap() is that this method is looking on internal elements array
     * as on the array of arrays (list of maps).
     *
     * @param array $newKeysMap Key-value pairs, where key is a key of stream and value is a new key.
     * @param bool  $skipOnFail Suppress exception if key not found in stream.
     *
     * @return static
     */
    public function remapAll(array $newKeysMap, bool $skipOnFail = true): static;

    /**
     * Changes the keys/indexes of the internal elements array
     * in the stream by applying the given function on each key-value pair.
     * The function should return a new key for the element.
     *
     * @param callable $func
     *
     * @return static
     */
    public function remapBy(callable $func): static;
}