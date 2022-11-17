<?php

namespace Pingframework\Streams\Tests;

use ArrayObject;
use InvalidArgumentException;
use LogicException;
use PhpOption\Option;
use PHPUnit\Framework\TestCase;
use Pingframework\Streams\Helpers\is;
use Pingframework\Streams\Stream;
use stdClass;

class StreamTest extends TestCase
{
    public function testCreationFromIterator()
    {
        $actual = Stream::of(new ArrayObject([1, 2, 3]))
            ->toMap();

        $this->assertSame([1, 2, 3], $actual);
    }

    public function testMap()
    {
        $actual = Stream::of(['A', 'b', 'C'])
            ->map(function ($value, $index) {
                return strtolower($value) . $index;
            })
            ->toList();

        $this->assertSame(['a0', 'b1', 'c2'], $actual);
    }

    public function testToGenerator()
    {
        $gen = Stream::of(['A', 'b', 'C'])
            ->toGenerator();

        $actual = [];
        foreach ($gen as $value) {
            $actual[] = $value;
        }

        $this->assertSame(['A', 'b', 'C'], $actual);
    }

    public function testOfGenerator()
    {
        $gen = Stream::of(['A' => 10, 'b' => 20, 'C' => 30])
            ->toGenerator();

        $actual = Stream::of($gen)
            ->toMap();

        $this->assertSame(['A' => 10, 'b' => 20, 'C' => 30], $actual);
    }

    public function testFilter()
    {
        $test = $this;

        $actual = Stream::of([1, 2, 3, 4, 5])
            ->filter(function ($value, $index) use ($test) {
                $test->assertEquals(1, $value - $index);

                return $value % 2 == 0;
            })
            ->toList();

        $this->assertSame([2, 4], $actual);
    }

    public function testUnique()
    {
        $actual = Stream::of([1, 1, 2, 3, 2, 0, 1])
            ->unique()
            ->toList();

        $this->assertSame([1, 2, 3, 0], $actual);
    }

    public function testJoin_givenEmptyArray_expectEmptyString()
    {
        $actual = Stream::of([])
            ->join(',');

        $this->assertSame('', $actual);
    }

    public function testJoin_givenNotEmptyArray_expectJoinedArray()
    {
        $actual = Stream::of([1, 2, '3', new StreamTest_String('4')])
            ->join(',');

        $this->assertSame('1,2,3,4', $actual);
    }

    public function testFirst_givenEmptyArray_expectNoneOption()
    {
        $actual = Stream::of([])
            ->first();

        $this->assertTrue($actual->isEmpty());
    }

    public function testFirst_givenArrayWithValues_expectSomeFirstValue()
    {
        $actual = Stream::of([3, 2, 1, 4])
            ->first();

        $this->assertOptionWithValue(3, $actual);
    }

    public function testFirst_givenArrayWithValues_givenKeysAreNotSequential_expectSomeFirstValue()
    {
        $actual = Stream::of([9 => 5, 0 => 2])
            ->first();

        $this->assertOptionWithValue(5, $actual);
    }

    public function testLast_givenEmptyArray_expectNoneOption()
    {
        $actual = Stream::of([])
            ->last();

        $this->assertTrue($actual->isEmpty());
    }

    public function testLast_givenArrayWithValues_expectSomeLastValue()
    {
        $actual = Stream::of([3, 2, 1, 4])
            ->last();

        $this->assertOptionWithValue(4, $actual);
    }

    public function testLast_givenArrayWithValues_givenKeysAreNotSequential_expectSomeLastValue()
    {
        $actual = Stream::of([7 => 3, 3 => 1, 10 => 5])
            ->last();

        $this->assertTrue($actual->isDefined());
        $this->assertSame(5, $actual->get());
    }

    public function testOrder_preserveKeys()
    {
        $actual = Stream::of([4, 1, 3, 2])
            ->sortAsc()
            ->toMap();

        $this->assertSame([1 => 1, 3 => 2, 2 => 3, 0 => 4], $actual);
    }

    public function testOrder_givenComparator_expectOrderDefinedByComparator()
    {
        $actual = Stream::of([4, 1, 3, 2])
            ->sort(function ($a, $b) {
                return $b - $a;
            })
            ->toMap();

        $this->assertSame([0 => 4, 2 => 3, 3 => 2, 1 => 1], $actual);
    }

    public function testMax()
    {
        $actual = Stream::of([3, 5, 1, 4])
            ->max();

        $this->assertOptionWithValue(5, $actual);
    }

    public function testMax_givenReversedComparator_expectMin()
    {
        $actual = Stream::of([3, 5, 1, 4])
            ->max(function ($a, $b) {
                return $b - $a;
            });

        $this->assertOptionWithValue(1, $actual);
    }

    public function testMax_givenEmptyArray_returnNone()
    {
        $actual = Stream::of([])
            ->max();

        $this->assertTrue($actual->isEmpty());
    }

    public function testMaxBy()
    {
        $actual = Stream::of([
            ['age' => 5],
            ['age' => 3],
            ['age' => 24],
            ['age' => 8],
        ])->maxBy(function ($value) {
            return $value['age'];
        });

        $this->assertOptionWithValue(['age' => 24], $actual);
    }

    public function testMin()
    {
        $actual = Stream::of([3, 5, 1, 4])
            ->min();

        $this->assertOptionWithValue(1, $actual);
    }

    public function testMin_givenReversedComparator_expectMax()
    {
        $actual = Stream::of([3, 5, 1, 4])
            ->min(function ($a, $b) {
                return $b - $a;
            });

        $this->assertOptionWithValue(5, $actual);
    }

    public function testMin_givenEmptyArray_returnNone()
    {
        $actual = Stream::of([])
            ->min();

        $this->assertTrue($actual->isEmpty());
    }


    public function testMinBy()
    {
        $actual = Stream::of([
            ['age' => 5],
            ['age' => 3],
            ['age' => 24],
            ['age' => 8],
        ])->minBy(function ($value) {
            return $value['age'];
        });

        $this->assertOptionWithValue(array('age' => 3), $actual);
    }

    public function testFirstMatch_givenEmptyArray_returnNone()
    {
        $actual = Stream::of([])
            ->firstMatch(function () {
                return true;
            });

        $this->assertTrue($actual->isEmpty());
    }

    public function testFirstMatch_givenArrayWithValues_noneValueMatches_returnNone()
    {
        $actual = Stream::of([1, 2, 3, 4])
            ->firstMatch(function () {
                return false;
            });

        $this->assertTrue($actual->isEmpty());
    }

    public function testFirstMatch_givenArrayWithValues_fewValuesMatches_expectFirst()
    {
        $test = $this;

        $actual = Stream::of([1, 2, 3, 4, 5, 6])
            ->firstMatch(function ($value, $index) use ($test) {
                $test->assertEquals(1, $value - $index);

                return $value % 2 === 0;
            });

        $this->assertOptionWithValue(2, $actual);
    }

    public function testReduce_givenEmptyArray_returnNone()
    {
        $actual = Stream::of([])
            ->reduce(function ($a, $b) {
                return $a + $b;
            });

        $this->assertTrue($actual->isEmpty());
    }

    public function testReduce_givenArrayWithValue_returnProduce()
    {
        $actual = Stream::of([1, 2, 3])
            ->reduce(function ($a, $b) {
                return $a + $b;
            });

        $this->assertOptionWithValue(6, $actual);
    }

    public function testReduceFromIdentity_givenEmptyArray_expectIdentity()
    {
        $actual = Stream::of([])
            ->reduce(function ($a, $b) {
                return $a + $b;
            }, 5);

        $this->assertOptionWithValue(5, $actual);
    }

    public function testReduceFromIdentity_givenArrayWithValues_expectProduct()
    {
        $actual = Stream::of([3, 2, 1])
            ->reduce(function ($a, $b) {
                return $a + $b;
            }, 2);

        $this->assertOptionWithValue(8, $actual);
    }

    public function testAllMatch_givenEmptyArray_expectTrue()
    {
        $actual = Stream::of([])
            ->allMatch(function () {
                return true;
            });

        $this->assertTrue($actual);
    }

    public function testAllMatchOrThrow()
    {
        Stream::of([new StdClass(), new StdClass()])
            ->allMatchOrThrow(is::instanceOf(StdClass::class));

        $this->expectException(InvalidArgumentException::class);
        Stream::of([new StdClass(), "", new StdClass()])
            ->allMatchOrThrow(is::instanceOf(StdClass::class));
    }

    public function testAllMatch_givenArrayWithValue_fewValuesMatch_expectFalse()
    {
        $test = $this;

        $actual = Stream::of([1, 2, 3, 4])
            ->allMatch(function ($value, $index) use ($test) {
                $test->assertEquals(1, $value - $index);

                return $value % 2 === 0;
            });

        $this->assertFalse($actual);
    }

    public function testAllMatch_givenArrayWithValue_allValuesMatch_expectTrue()
    {
        $actual = Stream::of([1, 1, 1, 1])
            ->allMatch(function ($value) {
                return $value === 1;
            });

        $this->assertTrue($actual);
    }

    public function testAnyMatch_givenEmptyArray_returnFalse()
    {
        $actual = Stream::of([])
            ->anyMatch(function ($value) {
                return true;
            });

        $this->assertFalse($actual);
    }

    public function testAnyMatch_givenArrayWithValues_givenValuesDoNotMatch_returnFalse()
    {
        $actual = Stream::of([1, 3, 5])
            ->anyMatch(function ($value) {
                return $value % 2 === 0;
            });

        $this->assertFalse($actual);
    }

    public function testAnyMatch_givenArrayWithValues_someValuesMatch_returnTrue()
    {
        $test = $this;

        $actual = Stream::of([1, 2, 3])
            ->anyMatch(function ($value, $index) use ($test) {
                $test->assertEquals(1, $value - $index);

                return $value % 2 === 0;
            });

        $this->assertTrue($actual);
    }

    public function testNoneMatch_givenEmptyArray_expectTrue()
    {
        $actual = Stream::of([])
            ->noneMatch(function ($value) {
                return false;
            });

        $this->assertTrue($actual);
    }

    public function testNoneMatch_givenArrayWithValue_allGivenValuesMatch_expectFalse()
    {
        $actual = Stream::of([2, 4, 6])
            ->noneMatch(function ($value) {
                return $value % 2 === 0;
            });

        $this->assertFalse($actual);
    }

    public function testNoneMatch_givenArrayWithValue_allGivenValuesDoNotMatch_expectTrue()
    {
        $actual = Stream::of([1, 3, 5])
            ->noneMatch(function ($value) {
                return $value % 2 === 0;
            });

        $this->assertTrue($actual);
    }

    public function testGroupBy()
    {
        $test = $this;

        $actual = Stream::ofRange(1, 9)
            ->group(function ($value, $index) use ($test) {
                $test->assertEquals(1, $value - $index);

                return $value % 3;
            })
            ->toMap();

        $this->assertSame([
            1 => [1, 4, 7],
            2 => [2, 5, 8],
            0 => [3, 6, 9],
        ], $actual);
    }

    public function testRemapBy()
    {
        $test = $this;

        $actual = Stream::ofRange(1, 4)
            ->remapBy(function ($value, $index) use ($test) {
                $test->assertEquals(1, $value - $index);

                return $value + 3;
            })
            ->toMap();

        $this->assertSame([
            4 => 1,
            5 => 2,
            6 => 3,
            7 => 4,
        ], $actual);
    }

    public function testRemap()
    {
        $test = $this;

        $actual = Stream::of(['a' => 1, 'b' => 2, 'c' => 3])
            ->remap(['a' => 'd', 'b' => 'e', 'c' => 'f'])
            ->toMap();

        $this->assertSame([
            'd' => 1,
            'e' => 2,
            'f' => 3,
        ], $actual);
    }

    public function testRemap_keyNotExists()
    {
        $test = $this;

        $actual = Stream::of(['a' => 1, 'b' => 2, 'c' => 3])
            ->remap(['x' => 'z'])
            ->toMap();

        $this->assertSame([
            'a' => 1,
            'b' => 2,
            'c' => 3,
        ], $actual);
    }

    public function testRemapAll()
    {
        $test = $this;

        $actual = Stream::of([['a' => 1, 'b' => 2, 'c' => 3]])
            ->remapAll(['a' => 'd', 'b' => 'e', 'c' => 'f'])
            ->toMap();

        $this->assertSame([[
            'd' => 1,
            'e' => 2,
            'f' => 3,
        ]], $actual);
    }

    public function testRemapAll_keyNotExists()
    {
        $test = $this;

        $actual = Stream::of([['a' => 1, 'b' => 2, 'c' => 3]])
            ->remapAll(['x' => 'z', 'a' => 'A'])
            ->toMap();

        $this->assertSame([[
            'A' => 1,
            'b' => 2,
            'c' => 3,
        ]], $actual);
    }

    public function testRemapBy_indexCollision_acceptLastElement()
    {
        $actual = Stream::ofRange(1, 4)
            ->remapBy(fn(int $value): int => 1)
            ->toMap();

        $this->assertSame([1 => 4], $actual);
    }

    public function testPartition_givenEmptyArray_returnTwoEmptyArrays()
    {
        $actual = Stream::of([])
            ->partition(function () {
                return true;
            })
            ->toMap();

        $this->assertSame([
            [],
            [],
        ], $actual);
    }

    public function testPartition_givenArrayWithOneElement_givenElementSatisfiesPredicate_returnValidArray()
    {
        $actual = Stream::of([5])
            ->partition(function () {
                return true;
            })
            ->toMap();

        $this->assertSame([
            [5],
            [],
        ], $actual);
    }

    public function testPartition_givenArrayWithRangeOfNumbers_makeParityPartition()
    {
        $test = $this;

        $actual = Stream::ofRange(1, 9)
            ->partition(function ($value, $index) use ($test) {
                $test->assertEquals(1, $value - $index);

                return $value % 2 === 0;
            })
            ->toMap();

        $this->assertSame([
            [2, 4, 6, 8],
            [1, 3, 5, 7, 9],
        ], $actual);
    }

    public function testSkip_givenArrayWithFewElements_givenSkipGreaterThanArrayLength_expectEmptyArray()
    {
        $actual = Stream::of([1, 2, 3, 4])
            ->skip(5)
            ->toList();

        $this->assertEmpty($actual);
    }

    public function testSkip_givenArrayWithFewElements_givenSkipLessThanArrayLength_expectSubarray()
    {
        $actual = Stream::of([1, 2, 3, 4, 5])
            ->skip(2)
            ->toList();

        $this->assertSame([3, 4, 5], $actual);
    }

    public function testSkip_givenSkipByZero_returnTheSameArray()
    {
        $actual = Stream::of([1, 2, 3])
            ->skip(0)
            ->toList();

        $this->assertSame([1, 2, 3], $actual);
    }

    public function testLimit_givenLimitGreaterThanArrayLength_returnTheSameArray()
    {
        $actual = Stream::of([1, 2, 3])
            ->limit(10)
            ->toList();

        $this->assertSame([1, 2, 3], $actual);
    }

    public function testLimit_givenZeroLimit_returnEmptyArray()
    {
        $actual = Stream::of([1, 2])
            ->limit(0)
            ->toList();

        $this->assertEmpty($actual);
    }

    public function testLimit_givenLimitLessThanArrayLength_returnSubarray()
    {
        $actual = Stream::of([1, 2, 3, 4, 5])
            ->limit(3)
            ->toList();

        $this->assertSame([1, 2, 3], $actual);
    }

    public function testIntersection()
    {
        $actual = Stream::of([1, 2, 3, 4, 5])
            ->intersect([3, 4, 6])
            ->toList();

        $this->assertSame([3, 4], $actual);
    }

    public function testDifference()
    {
        $actual = Stream::of([1, 2, 3, 4])
            ->diff([2, 4, 6])
            ->toList();

        $this->assertSame([1, 3], $actual);
    }

    public function testCollect()
    {
        $actual = Stream::of([1, 2, 3])
            ->collect(function ($elements) {
                return new ArrayObject($elements);
            });

        $this->assertEquals(new ArrayObject([1, 2, 3]), $actual);
    }

    public function testFlatMap()
    {
        $actual = Stream::of(['some', 'text', 'to', 'flat', 'map'])
            ->flatMap(function ($value) {
                return str_split($value);
            })
            ->toList();

        $this->assertSame(['s', 'o', 'm', 'e', 't', 'e', 'x', 't', 't', 'o', 'f', 'l', 'a', 't', 'm', 'a', 'p'],
            $actual);
    }

    public function testFlatten()
    {
        $actual = Stream::of([
            [1, 2],
            [3, 4, [5]]
        ])
            ->flat()
            ->toList();

        $this->assertSame([1, 2, 3, 4, [5]], $actual);
    }

    public function testFlatten_oneValueIsNotAnArray_throwEx()
    {
        $this->expectException(LogicException::class);
        Stream::of([[3], 4])
            ->flat()
            ->toList();
    }

    public function testKeys()
    {
        $actual = Stream::of(['key1' => 'value1', 'key2' => 'value2'])
            ->keys()
            ->toList();

        $this->assertEquals(['key1', 'key2'], $actual);
    }

    public function testKeysSearch()
    {
        $actual = Stream::of(['key1' => 'value1', 'key2' => 'value2'])
            ->keys('value2')
            ->toList();

        $this->assertEquals(['key2'], $actual);
    }

    public function testToEmptyArrayObject()
    {
        $result = Stream::of([1, 2, 3])
            ->toArrayObject();

        $this->assertInstanceOf(ArrayObject::class, $result);
        $this->assertEquals([1, 2, 3], $result->getArrayCopy());
    }

    public function testToExistingArrayObject()
    {
        $result = Stream::of([1, 2, 3])
            ->toArrayObject(new ArrayObject([4, 5, 6]));

        $this->assertInstanceOf(ArrayObject::class, $result);
        $this->assertEquals([4, 5, 6, 1, 2, 3], $result->getArrayCopy());
    }

    public function testToExistingArrayObjectWithKeys()
    {
        $result = Stream::of(['a' => 1, 'b' => 2, 'c' => 3])
            ->toArrayObject(new ArrayObject(['d' => 4, 'e' => 5, 'a' => 6]));

        $this->assertInstanceOf(ArrayObject::class, $result);
        $this->assertEquals(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], $result->getArrayCopy());
    }

    public function testGet_valueExists()
    {
        $actual = Stream::of([1, 2, 3])
            ->get(1);

        $this->assertOptionWithValue(2, $actual);
    }

    public function testGet_valueExistsButItIsFalse_expectedSome()
    {
        $actual = Stream::of([true, false])
            ->get(1);

        $this->assertOptionWithValue(false, $actual);
    }

    public function testGet_valueExistsButIsNull_expectedNone()
    {
        $actual = Stream::of([null, false])
            ->get(0);

        $this->assertFalse($actual->isDefined());
    }

    public function testGet_valueDoesNotExist_expectedNone()
    {
        $actual = Stream::of([null, false])
            ->get(3);

        $this->assertFalse($actual->isDefined());
    }

    public function testZip_givenShorterTraversable_useNullOnMissingKeys()
    {
        $result = Stream::of([1, 2, 3])
            ->zip(['a', 2 => 'c'])
            ->toMap();

        $this->assertEquals([[1, 'a'], [2, 'c'], [3, null]], $result);
    }

    public function testZip_givenCombiningFunction_useTheFunctionToCombineValues()
    {
        $result = Stream::of([1, 2, 3])
            ->zip(['a', 2 => 'c'], function ($a, $b) {
                return $a . $b;
            })
            ->toList();

        $this->assertEquals(['1a', '2c', '3'], $result);
    }

    /**
     * @test
     * @dataProvider sortByProvider
     */
    public function testOrderBy_givenUnsortedValues_sortIt($values, $orderBy, $direction, $expected)
    {
        $actual = Stream::of($values)
            ->sortBy($orderBy, $direction)
            ->toList();

        $this->assertSame($expected, $actual);
    }

    public function testMerge()
    {
        $actual = Stream::of(['a' => 'A', 'b' => 'B', 1, 2, 3])
            ->merge(['a' => 'C', 1, 5])
            ->toMap();

        $this->assertSame(['a' => 'C', 'b' => 'B', 1, 2, 3, 1, 5], $actual);
    }

    /**
     * @test
     * @dataProvider resetArrayInternalPointerProvider
     */
    public function testResetArrayInternalPointer($method, $func = null)
    {
        $actual = Stream::of([1, 2, 3, 4, 5, 6])
            ->$method(
                $func
            )
            ->first()
            ->getOrElse(null);

        $this->assertNotNull($actual);
    }

    public function resetArrayInternalPointerProvider(): array
    {
        return [
            [
                'map',
                fn($value) => $value,
            ],
            [
                'zip',
                [1, 2, 3],
            ],
            [
                'filter',
                fn($value) => true,
            ],
            [
                'group',
                fn($value) => $value,
            ],
            [
                'remap',
                fn($value) => $value,
            ],
            [
                'partition',
                fn($value) => $value % 2 === 0
            ]
        ];
    }

    public function sortByProvider(): array
    {
        return [
            [
                [
                    ['name' => 'T'],
                    ['name' => 'Z'],
                    ['name' => 'A'],
                ],
                fn($element) => $element['name'],
                'ASC',
                [
                    ['name' => 'A'],
                    ['name' => 'T'],
                    ['name' => 'Z'],
                ],
            ],
            [
                [
                    ['name' => 'T'],
                    ['name' => 'Z'],
                    ['name' => 'A'],
                ],
                fn($element) => $element['name'],
                'DESC',
                [
                    ['name' => 'Z'],
                    ['name' => 'T'],
                    ['name' => 'A'],
                ],
            ],
        ];
    }

    private function assertOptionWithValue($expected, Option $actual)
    {
        $this->assertTrue($actual->isDefined());
        $this->assertSame($expected, $actual->get());
    }
}

class StreamTest_String
{
    private string $string;

    function __construct(string $string)
    {
        $this->string = $string;
    }

    function __toString()
    {
        return $this->string;
    }
}
