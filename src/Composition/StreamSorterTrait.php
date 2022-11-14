<?php

namespace Pingframework\Streams\Composition;

trait StreamSorterTrait
{
    use StreamElementsTrait;
    use StreamComparatorTrait;

    /**
     * Sorts elements in place in ascending order,
     * such that its keys maintain their correlation with the values they are associated with.
     *
     * The optional flags parameter may be used to modify the sorting behavior using these values:
     * Sorting type flags:
     *      SORT_REGULAR - compare items normally; the details are described in the comparison operators section
     *      SORT_NUMERIC - compare items numerically
     *      SORT_STRING - compare items as strings
     *      SORT_LOCALE_STRING - compare items as strings, based on the current locale. It uses the locale, which can be changed using setlocale()
     *      SORT_NATURAL - compare items as strings using "natural ordering" like natsort()
     *      SORT_FLAG_CASE - can be combined (bitwise OR) with SORT_STRING or SORT_NATURAL to sort strings case-insensitively
     *
     * @param int $flags
     * @return $this
     */
    public function sortAsc(int $flags = SORT_REGULAR): static
    {
        asort($this->elements, $flags);
        return $this;
    }

    /**
     * Sorts elements in place in descending order,
     * such that its keys maintain their correlation with the values they are associated with.
     *
     * The optional flags parameter may be used to modify the sorting behavior using these values:
     * Sorting type flags:
     *      SORT_REGULAR - compare items normally; the details are described in the comparison operators section
     *      SORT_NUMERIC - compare items numerically
     *      SORT_STRING - compare items as strings
     *      SORT_LOCALE_STRING - compare items as strings, based on the current locale. It uses the locale, which can be changed using setlocale()
     *      SORT_NATURAL - compare items as strings using "natural ordering" like natsort()
     *      SORT_FLAG_CASE - can be combined (bitwise OR) with SORT_STRING or SORT_NATURAL to sort strings case-insensitively
     *
     * @param int $flags
     * @return $this
     */
    public function sortDesc(int $flags = SORT_REGULAR): static
    {
        arsort($this->elements, $flags);
        return $this;
    }

    /**
     * Sorts elements in place such that its keys maintain their correlation with the values
     * they are associated with, using a user-defined comparison function.
     *
     * @param callable $func Comparing function
     *
     * @return static
     */
    public function sort(callable $func): static
    {
        uasort($this->elements, $func);
        return $this;
    }

    /**
     * Sorts elements using provided function as value provider for objects.
     *
     * Example:
     * <code>
     *      Stream::of([
     *              ['name' => 'John'],
     *              ['name' => 'Jolka'],
     *          ]
     *
     *          // sort by "name" property
     *          ->sortBy(get::value('name'))
     *          ->toList();
     * </code>
     *
     * @param callable $valFunc
     * @param string   $direction ASC or DESC - direction of ordering
     * @return static
     */
    public function sortBy(callable $valFunc, string $direction = 'ASC'): static
    {
        $this->sort($this->comparatorFromProducer($valFunc, strtoupper($direction) === 'ASC' ? 1 : -1));
        return $this;
    }
}