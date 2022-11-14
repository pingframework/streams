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

use ArrayObject;

/**
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */
interface StreamConvertable
{
    /**
     * Returns list of elements - indexes are ignored, all values are re-indexed.
     * Use this function when indexes in use context have not meaning.
     *
     * @return array
     */
    public function toList(): array;

    /**
     * Returns array of elements - indexes are preserved.
     * Use this function when indexes in use context have meaning.
     *
     * @return array
     */
    public function toMap(): array;

    /**
     * Maps internal key-value array to passed object and returns it back,
     * where key is an object's property and value is a object's property value.
     *
     * @param object $object
     *
     * @return object
     */
    public function toObject(object $object): object;

    /**
     * Converts stream to array object.
     * If $object is not passed, then new ArrayObject instance will be created.
     * If $object is passed, then it will be filled with internal array, all existing keys will be overwritten.
     *
     * @param ArrayObject|null $object ArrayObject instance.
     *
     * @return object
     */
    public function toArrayObject(?ArrayObject $object = null): object;
}