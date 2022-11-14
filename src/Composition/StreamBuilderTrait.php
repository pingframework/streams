<?php

namespace Pingframework\Streams\Composition;

use Pingframework\Streams\Interfaces\StreamInterface;
use Pingframework\Streams\Stream;
use Traversable;

trait StreamBuilderTrait
{
    /**
     * Builds new stream.
     *
     * @param iterable $iterable Iterable to build stream from.
     *
     * @return StreamInterface
     */
    static public function of(iterable $iterable): StreamInterface
    {
        $stream = new Stream();
        $stream->elements = $iterable instanceof Traversable
            ? iterator_to_array($iterable)
            : (array)$iterable;
        return $stream;
    }

    /**
     * Builds new stream from given object.
     *
     * @param object ...$object Object to build stream from.
     *
     * @return StreamInterface
     */
    static public function ofObject(object ...$object): StreamInterface
    {
        $stream = new Stream();
        array_push($stream->elements, ...array_map('get_object_vars', $object));
        return $stream;
    }

    /**
     * Builds new stream from given string split by given separator.
     *
     * @param string $separator String separator.
     * @param string ...$string String to build stream from.
     *
     * @return StreamInterface
     */
    static public function ofString(string $separator = '', string ...$string): StreamInterface
    {
        $stream = new Stream();
        array_push($stream->elements, ...array_map(fn($string) => explode($separator, $string), $string));
        return $stream;
    }

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
    ): StreamInterface {
        $stream = new Stream();
        array_push($stream->elements, ...range($start, $end, $step));
        return $stream;
    }
}