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

use ArrayAccess;
use RuntimeException;

/**
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */
class PropertyGetter
{
    public function getValue(mixed $object, mixed $path = null): mixed
    {
        if ($path === null) {
            return $object;
        }
        if (!is_string($path) && is_callable($path)) {
            return call_user_func($path, $object);
        }

        $properties = explode('.', $path);

        foreach ($properties as $property) {
            $object = self::readProperty($object, $property);
        }

        return $object;
    }

    private static function readProperty(object|array $object, mixed $property): mixed
    {
        if (is_array($object)) {
            return $object[$property] ?? null;
        }

        $getters = ['get' . $property, 'get' . $property, 'has' . $property, $property];

        foreach ($getters as $getter) {
            if (is_callable([$object, $getter])) {
                return $object->$getter();
            }
        }

        if (property_exists($object, $property)) {
            return $object->$property;
        }

        if ($object instanceof ArrayAccess) {
            return $object[$property];
        }

        throw new RuntimeException(sprintf('Property "%s" cannot be read', $property));
    }
}