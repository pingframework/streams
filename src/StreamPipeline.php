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

namespace Pingframework\Streams;

use PhpOption\Option;
use Pingframework\Streams\Pipeline\PipelineNonCallablePuppet;
use Pingframework\Streams\Pipeline\PipelinePuppet;
use Pingframework\Streams\Pipeline\PipelineTypeEnum;
use RuntimeException;

/**
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */
class StreamPipeline
{
    private array            $operations        = array();
    private ?string          $terminalOperation = null;
    private PipelineTypeEnum $argumentType;

    protected function __construct(PipelineTypeEnum $argType)
    {
        $this->argumentType = $argType;
    }

    /**
     * Creates pipeline that creates function accepting one iterable argument
     *
     * @return static
     */
    public static function forIterable(): static
    {
        return new static(PipelineTypeEnum::Iterable);
    }

    /**
     * Creates pipeline that creates function accepting varargs, each argument is threaten as an array element
     *
     * @return static
     */
    public static function forVarargs(): static
    {
        return new static(PipelineTypeEnum::Varargs);
    }

    /**
     * Creates pipeline that creates function accepting one argument that will be only element of array. This factory
     * method is similar to {@link StreamPipeline::forVarargs()}, the difference is all arguments are ignored except
     * the first.
     *
     * @return static
     */
    public static function forValue(): static
    {
        return new static(PipelineTypeEnum::Value);
    }

    /**
     * @param mixed $iterable
     *
     * @return mixed Depends on pipeline instrumentation
     */
    public function __invoke(mixed ...$iterable): mixed
    {
        switch ($this->argumentType) {
            case PipelineTypeEnum::Varargs:
                $iterable = func_get_args();
                break;
            case PipelineTypeEnum::Value:
                $iterable = func_num_args() ? [func_get_arg(0)] : [];
                break;
            case PipelineTypeEnum::Iterable:
                $iterable = func_num_args() ? func_get_arg(0) : [];
        }

        $result = Stream::of($iterable);

        foreach ($this->operations as $operation) {
            if ($operation instanceof PipelinePuppet) {
                $result = $operation($result);
            } else {
                [$method, $args] = $operation;
                $result = call_user_func_array([$result, $method], $args);
            }
        }

        return $result;
    }

    private function markTerminalOperation(string $method): void
    {
        $this->checkTerminalOperation();
        $this->terminalOperation = $method;
    }

    private function checkTerminalOperation(): void
    {
        if ($this->terminalOperation !== null) {
            throw new RuntimeException(
                sprintf(
                    'Only one terminal operation can be called, "%s" has been already called.',
                    $this->terminalOperation
                )
            );
        }
    }

    /**
     * @return PipelineNonCallablePuppet
     */
    private function nonCallablePuppet(): PipelineNonCallablePuppet
    {
        $puppet = PipelineNonCallablePuppet::record();
        $this->operations[] = $puppet;
        return $puppet;
    }

    public function sum(): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function sumBy(callable $valFunc): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function collect(callable $collector): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function toList(): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function toMap(): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function toObject(object $object): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function diff(iterable $iterable): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function append(mixed $element): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function prepend(mixed $element): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function filter(callable $predicate): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function flatMap(callable $func): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function flat(): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function get(mixed $index): PipelineNonCallablePuppet
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this->nonCallablePuppet();
    }

    public function first(): PipelineNonCallablePuppet
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this->nonCallablePuppet();
    }

    public function last(): PipelineNonCallablePuppet
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this->nonCallablePuppet();
    }

    public function group(callable $func): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function intersect(iterable $iterable): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function forEach(callable $func): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function forEachRecursive(callable $func): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function join(string $separator = ''): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function keys(mixed $search_value, bool $strict = false): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function values(): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function limit(int $n): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function map(callable $func): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function allMatch(callable $predicate): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function anyMatch(callable $predicate): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function noneMatch(callable $predicate): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function firstMatch(callable $predicate): PipelineNonCallablePuppet
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this->nonCallablePuppet();
    }

    public function merge(iterable $iterable): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function mergeRecursive(iterable $iterable): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function partition(callable $predicate): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function reduce(callable $binaryOperator, mixed $initial = null): PipelineNonCallablePuppet
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this->nonCallablePuppet();
    }

    public function remap(callable $func): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function reverse(): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    /**
     * @param callable|null $comparator
     * @return PipelineNonCallablePuppet|Option
     */
    public function max(callable $comparator = null): PipelineNonCallablePuppet
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this->nonCallablePuppet();
    }

    public function maxBy(callable $valFunc): PipelineNonCallablePuppet
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this->nonCallablePuppet();
    }

    public function min(callable $comparator = null): PipelineNonCallablePuppet
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this->nonCallablePuppet();
    }

    public function minBy(callable $valFunction): PipelineNonCallablePuppet
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this->nonCallablePuppet();
    }

    public function size(): static
    {
        $this->markTerminalOperation(__FUNCTION__);
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function skip(int $n): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function slice(int $offset, ?int $length = null): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function sortAsc(int $flags = SORT_REGULAR): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function sortDesc(int $flags = SORT_REGULAR): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function sort(callable $func): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function sortBy(callable $valFunc, string $direction = 'ASC'): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function unique(): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }

    public function zip(iterable $iterable, ?callable $func = null): static
    {
        $this->checkTerminalOperation();
        $this->operations[] = array(__FUNCTION__, func_get_args());
        return $this;
    }
}