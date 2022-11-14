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

use Pingframework\Streams\Interfaces\StreamInterface;
use Pingframework\Streams\Stream;

/**
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */
class Predicates
{
    private static ?PropertyGetter $propertyGetter = null;

    private static function getPropertyGetter(): PropertyGetter
    {
        if (self::$propertyGetter === null) {
            self::$propertyGetter = new PropertyGetter();
        }

        return self::$propertyGetter;
    }

    public static function eq(mixed $property, mixed $value = null): callable
    {
        [$property, $value] = self::fixBinaryArgs($property, $value, func_num_args());
        $propertyGetter = self::getPropertyGetter();
        return fn(mixed $object): bool => $propertyGetter->getValue($object, $property) == $value;
    }

    public static function notEq(mixed $property, mixed $value = null): callable
    {
        return self::not(call_user_func_array([__CLASS__, 'eq'], func_get_args()));
    }

    private static function fixBinaryArgs(mixed $property, mixed $value, int $argsCount): array
    {
        return $argsCount === 1
            ? [null, $property]
            : [$property, $value];
    }

    public static function identical(mixed $property, mixed $value = null): callable
    {
        [$property, $value] = self::fixBinaryArgs($property, $value, func_num_args());
        $propertyGetter = self::getPropertyGetter();
        return fn(mixed $object): bool => $propertyGetter->getValue($object, $property) === $value;
    }

    public static function notIdentical(mixed $property, mixed $value = null): callable
    {
        //call_user_* instead self::identical() to maintain original number of passed args
        return self::not(call_user_func_array([__CLASS__, 'identical'], func_get_args()));
    }

    public static function false(mixed $property = null): callable
    {
        return self::identical($property, false);
    }

    public static function notFalse(mixed $property = null): callable
    {
        return self::not(self::false($property));
    }

    /**
     * Not strict false predicate
     *
     * @param mixed $property
     * @return callable
     */
    public static function falsy(mixed $property = null): callable
    {
        return self::eq($property, false);
    }

    /**
     * Strict true predicate
     *
     * @param mixed $property
     * @return callable
     */
    public static function true(mixed $property = null): callable
    {
        return self::identical($property, true);
    }

    /**
     * Strict not true predicate
     *
     * @param mixed $property
     * @return callable
     */
    public static function notTrue(mixed $property = null): callable
    {
        return self::not(self::true($property));
    }

    /**
     * Not strict true predicate
     *
     * @param mixed $property
     * @return callable
     */
    public static function truthy(mixed $property = null): callable
    {
        return self::eq($property, true);
    }

    public static function gt(mixed $property, mixed $value = null): callable
    {
        [$property, $value] = self::fixBinaryArgs($property, $value, func_num_args());
        $propertyGetter = self::getPropertyGetter();
        return fn(mixed $object): bool => $propertyGetter->getValue($object, $property) > $value;
    }

    public static function lt(mixed $property, mixed $value = null): callable
    {
        [$property, $value] = self::fixBinaryArgs($property, $value, func_num_args());
        $propertyGetter = self::getPropertyGetter();
        return fn(mixed $object): bool => $propertyGetter->getValue($object, $property) < $value;
    }

    public static function gte(mixed $property, mixed $value = null): callable
    {
        [$property, $value] = self::fixBinaryArgs($property, $value, func_num_args());
        $propertyGetter = self::getPropertyGetter();
        return fn(mixed $object): bool => $propertyGetter->getValue($object, $property) >= $value;
    }

    public static function lte(mixed $property, mixed $value = null): callable
    {
        [$property, $value] = self::fixBinaryArgs($property, $value, func_num_args());
        $propertyGetter = self::getPropertyGetter();
        return fn(mixed $object): bool => $propertyGetter->getValue($object, $property) <= $value;
    }

    public static function not(callable $predicate): callable
    {
        return fn(mixed $object): bool => !$predicate($object);
    }

    public static function andX(callable $predicate1, ?callable $predicate2 = null): callable
    {
        /**
         * @var StreamInterface $predicates
         * @var StreamInterface $values
         */
        [$predicates, $values] = self::partitionPredicatesAndValues(func_get_args());

        if ($values->anyMatch(self::notTrue())) {
            return fn(): bool => false;
        }

        return fn(mixed $object): bool => $predicates->allMatch(fn(callable $predicate): bool => $predicate($object));
    }

    /**
     * Alias to {@link Predicates#andX()}
     *
     * @see andX
     */
    public static function allTrue(callable $predicate1, ?callable $predicate2 = null): callable
    {
        return call_user_func_array([__CLASS__, 'andX'], func_get_args());
    }

    public static function orX(callable $predicate1, ?callable $predicate2 = null): callable
    {
        /**
         * @var StreamInterface $predicates
         * @var StreamInterface $values
         */
        [$predicates, $values] = self::partitionPredicatesAndValues(func_get_args());

        if ($values->anyMatch(self::true())) {
            return fn(): bool => true;
        }

        return fn(mixed $object): bool => $predicates->anyMatch(fn(callable $predicate): bool => $predicate($object));
    }

    /**
     * Alias to {@link Predicates#orX()}
     *
     * @see orX
     */
    public static function anyTrue(callable $predicate1, ?callable $predicate2 = null): callable
    {
        return call_user_func_array([__CLASS__, 'orX'], func_get_args());
    }

    public static function in(mixed $property, mixed $values = null): callable
    {
        [$property, $values] = self::fixBinaryArgs($property, $values, func_num_args());
        $propertyGetter = self::getPropertyGetter();
        return fn(mixed $object): bool => in_array($propertyGetter->getValue($object, $property), $values);
    }

    public static function notIn(mixed $property, mixed $values = null): callable
    {
        return self::not(call_user_func_array([__CLASS__, 'in'], func_get_args()));
    }

    public static function notNull(mixed $property = null): callable
    {
        $propertyGetter = self::getPropertyGetter();
        return fn(mixed $object): bool => $propertyGetter->getValue($object, $property) !== null;
    }

    public static function null(mixed $property = null): callable
    {
        return self::identical($property, null);
    }

    public static function contains(mixed $property, mixed $needle = null): callable
    {
        [$property, $needle] = self::fixBinaryArgs($property, $needle, func_num_args());
        $propertyGetter = self::getPropertyGetter();
        return fn(mixed $object): bool => str_contains(
            (string)$propertyGetter->getValue($object, $property),
            (string)$needle
        );
    }

    public static function blank(mixed $property = null): callable
    {
        $propertyGetter = self::getPropertyGetter();
        return fn(mixed $object): bool => empty($propertyGetter->getValue($object, $property));
    }

    public static function notBlank(mixed $property = null): callable
    {
        return self::not(self::blank($property));
    }

    private static function partitionPredicatesAndValues(array $arguments): array
    {
        return Stream::of($arguments)
            ->partition(fn(mixed $arg): bool => is_callable($arg))
            ->map(fn(array $elements): StreamInterface => Stream::of($elements))
            ->toMap();
    }
}