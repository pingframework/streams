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
class BookBuilder
{
    private string $fullTitle     = '';
    private string $shortTitle    = '';
    private array  $authors       = [];
    private ?string $publisherName = null;
    private bool   $cool          = true;

    public static function create(): BookBuilder
    {
        return new self();
    }

    public function title(string $title): static
    {
        $this->fullTitle = $title;
        return $this;
    }

    public function shortTitle(string $title): static
    {
        $this->shortTitle = $title;
        return $this;
    }

    public function author(string $name): static
    {
        $this->authors[] = new Author($name);
        return $this;
    }

    public function publisher(?string $name): static
    {
        $this->publisherName = $name;
        return $this;
    }

    public function cool(bool $cool): static
    {
        $this->cool = $cool;
        return $this;
    }

    public function getBook(): Book
    {
        return new Book($this->fullTitle, $this->authors, $this->publisherName, $this->shortTitle, $this->cool);
    }
}