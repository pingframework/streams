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
interface StreamSortable
{
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
    public function sortAsc(int $flags = SORT_REGULAR): static;

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
    public function sortDesc(int $flags = SORT_REGULAR): static;

    /**
     * Sorts elements in place such that its keys maintain their correlation with the values
     * they are associated with, using a user-defined comparison function.
     *
     * @param callable $func Comparing function
     *
     * @return static
     */
    public function sort(callable $func): static;

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
    public function sortBy(callable $valFunc, string $direction = 'ASC'): static;
}