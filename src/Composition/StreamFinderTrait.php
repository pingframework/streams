<?php

namespace Pingframework\Streams\Composition;

use PhpOption\None;
use PhpOption\Option;

trait StreamFinderTrait
{
    use StreamElementsTrait;
    use StreamComparatorTrait;

    /**
     * Gets {@link Option} containing max element by natural order or by given comparator
     *
     * @param callable|null $comparator Comparing function
     *
     * @return Option
     *
     * @see min
     */
    public function max(callable $comparator = null): Option
    {
        if(!$this->elements) {
            return None::create();
        }

        if($comparator === null) {
            return Option::fromValue(max($this->elements));
        }

        $elements = $this->elements;
        usort($elements, $comparator);

        return Option::fromValue(end($elements));
    }

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
    public function maxBy(callable $valFunc): Option
    {
        return $this->max($this->comparatorFromProducer($valFunc, 1));
    }

    /**
     * Gets {@link Option} containing min element by natural order or by given comparator.
     *
     * @param callable|null $comparator Comparing function.
     * @return Option
     */
    public function min(callable $comparator = null): Option
    {
        if(!$this->elements) {
            return None::create();
        }

        if($comparator === null) {
            return Option::fromValue(min($this->elements));
        }

        $elements = $this->elements;
        usort($elements, $comparator);

        return Option::fromValue(reset($elements));
    }

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
    public function minBy(callable $valFunction): Option
    {
        return $this->min($this->comparatorFromProducer($valFunction, 1));
    }
}