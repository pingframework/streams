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

use Pingframework\Streams\Composition\StreamBuilderTrait;
use Pingframework\Streams\Composition\StreamCalculatorTrait;
use Pingframework\Streams\Composition\StreamCollectorTrait;
use Pingframework\Streams\Composition\StreamConvertorTrait;
use Pingframework\Streams\Composition\StreamDifferentiatorTrait;
use Pingframework\Streams\Composition\StreamElementsTrait;
use Pingframework\Streams\Composition\StreamExtendorTrait;
use Pingframework\Streams\Composition\StreamFiltererTrait;
use Pingframework\Streams\Composition\StreamFinderTrait;
use Pingframework\Streams\Composition\StreamFlatterTrait;
use Pingframework\Streams\Composition\StreamGetterTrait;
use Pingframework\Streams\Composition\StreamGrouperTrait;
use Pingframework\Streams\Composition\StreamIntersectorTrait;
use Pingframework\Streams\Composition\StreamIteratorTrait;
use Pingframework\Streams\Composition\StreamJoinerTrait;
use Pingframework\Streams\Composition\StreamKeyValueTrait;
use Pingframework\Streams\Composition\StreamLimiterTrait;
use Pingframework\Streams\Composition\StreamMapperTrait;
use Pingframework\Streams\Composition\StreamMatcherTrait;
use Pingframework\Streams\Composition\StreamMergerTrait;
use Pingframework\Streams\Composition\StreamPartitionTrait;
use Pingframework\Streams\Composition\StreamReducerTrait;
use Pingframework\Streams\Composition\StreamRemapperTrait;
use Pingframework\Streams\Composition\StreamReverserTrait;
use Pingframework\Streams\Composition\StreamSizerTrait;
use Pingframework\Streams\Composition\StreamSkipperTrait;
use Pingframework\Streams\Composition\StreamSlicerTrait;
use Pingframework\Streams\Composition\StreamSorterTrait;
use Pingframework\Streams\Composition\StreamUniquerTrait;
use Pingframework\Streams\Composition\StreamZipperTrait;
use Pingframework\Streams\Interfaces\StreamInterface;

/**
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */
class Stream implements StreamInterface
{
    use StreamElementsTrait;
    use StreamBuilderTrait;
    use StreamCalculatorTrait;
    use StreamCollectorTrait;
    use StreamConvertorTrait;
    use StreamDifferentiatorTrait;
    use StreamExtendorTrait;
    use StreamFiltererTrait;
    use StreamIteratorTrait;
    use StreamMapperTrait;
    use StreamKeyValueTrait;
    use StreamFlatterTrait;
    use StreamGetterTrait;
    use StreamGrouperTrait;
    use StreamIntersectorTrait;
    use StreamJoinerTrait;
    use StreamLimiterTrait;
    use StreamSlicerTrait;
    use StreamMatcherTrait;
    use StreamMergerTrait;
    use StreamPartitionTrait;
    use StreamReducerTrait;
    use StreamRemapperTrait;
    use StreamReverserTrait;
    use StreamFinderTrait;
    use StreamSizerTrait;
    use StreamSorterTrait;
    use StreamSkipperTrait;
    use StreamUniquerTrait;
    use StreamZipperTrait;

    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }
}