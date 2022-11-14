<?php

namespace Pingframework\Streams\Composition;

trait StreamZipperTrait
{
    use StreamElementsTrait;

    /**
     * Combines elements with given iterable.
     *
     * Example:
     * <code>
     *      $result = Stream::of([1, 2, 3])
     *          ->zip(['a', 'b', 'c'])
     *          ->toList();
     *      // [[1, 'a'], [2, 'b'], [3, 'c']]
     *
     *      // or when $func is provided
     *      $result = Stream::of([1, 2, 3])
     *          ->zip(['a', 'b', 'c'], fn(int $a, int $b): string => return $a . $b)
     *          ->toList();
     *      // ['1a', '2b', '3c']
     * </code>
     *
     * @param iterable      $iterable Iterable to combine with.
     * @param callable|null $func     Function to combine elements.
     *
     * @return static
     */
    public function zip(iterable $iterable, ?callable $func = null): static
    {
        $this->elements = array_map(
            $func ?? fn(mixed $a, mixed $b): array => [$a, $b],
            $this->elements,
            $iterable
        );

        return $this;
    }
}