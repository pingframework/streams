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

namespace Pingframework\Streams\Pipeline;

use ArrayAccess;
use BadMethodCallException;

/**
 * Class that is able to record behaviour and play this behaviour multiple times on various objects.
 *
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */
class PipelinePuppet implements ArrayAccess
{
    private array $movements = [];

    public static function record(): static
    {
        return new static();
    }

    public static function object(): static
    {
        return static::record();
    }

    public function play(mixed $object): mixed
    {
        return $this($object);
    }

    public function __get(mixed $name): static
    {
        $this->movements[] = fn(mixed $object): mixed => $object->$name ?? null;
        return $this;
    }

    public function __call(string $method, array $arguments): static
    {
        $this->movements[] = function (mixed $object) use ($method, $arguments): mixed {
            $callable = [$object, $method];

            if (!is_callable($callable)) {
                throw new BadMethodCallException(sprintf('Method %s::%s does not exist', get_class($object), $method));
            }

            return call_user_func_array($callable, $arguments);
        };

        return $this;
    }

    public function offsetGet(mixed $offset): static
    {
        $this->movements[] = fn(mixed $object): mixed => $object[$offset] ?? null;
        return $this;
    }

    public function __invoke(mixed $object): mixed
    {
        foreach ($this->movements as $movement) {
            if (is_object($object) || is_array($object)) {
                $object = $movement($object);
            } else {
                return null;
            }
        }

        return $object;
    }

    public function offsetExists(mixed $offset): bool
    {
        throw new BadMethodCallException('Not supported');
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new BadMethodCallException('Not supported');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new BadMethodCallException('Not supported');
    }
}