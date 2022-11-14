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

namespace Pingframework\Streams\Tests\Stub;

/**
 * @author    Oleg Bronzov <oleg.bronzov@gmail.com>
 * @copyright 2022
 * @license   https://opensource.org/licenses/MIT  The MIT License
 */
class Book
{
    public const FULL = 'full';
    public const SHORT = 'short';

    public array $authors;
    public ?Publisher $publisher;
    private array $titles;
    private bool $coolness;

    function __construct(string $title, array $authors, ?string $publisherName, ?string $shortTitle = null, bool $coolness = true)
    {
        $this->authors = $authors;
        $this->publisher = $publisherName ? new Publisher($publisherName) : null;

        $this->titles = array(
            self::FULL => $title,
            self::SHORT => $shortTitle ?: $title,
        );
        $this->coolness =  $coolness;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function getTitle($type = self::FULL): string
    {
        return $this->titles[$type];
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    public function isCool(): bool
    {
        return $this->coolness;
    }

}