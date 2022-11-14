<?php

namespace Pingframework\Streams\Composition;

trait StreamComparatorTrait
{
    protected function comparatorFromProducer(callable $valFunction, int $aGreaterValue): callable
    {
        return function (mixed $a, mixed $b) use ($valFunction, $aGreaterValue): int {
            $aVal = $valFunction($a);
            $bVal = $valFunction($b);

            if ($aVal > $bVal) return $aGreaterValue;
            if ($aVal < $bVal) return -$aGreaterValue;
            return 0;
        };
    }
}