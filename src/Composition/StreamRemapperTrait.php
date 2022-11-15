<?php

namespace Pingframework\Streams\Composition;

use InvalidArgumentException;

trait StreamRemapperTrait
{
    use StreamElementsTrait;
    use StreamMapperTrait;
    use StreamBuilderTrait;

    /**
     * Changes the keys/indexes of the internal elements array
     * in the stream by applying the given function on each key-value pair.
     * The function should return a new key for the element.
     *
     * @param callable $func
     *
     * @return static
     */
    public function remapBy(callable $func): static
    {
        $elements = [];

        foreach ($this->elements as $index => $value) {
            $newIndex = call_user_func($func, $value, $index);
            $elements[$newIndex] = $value;
        }

        $this->elements = $elements;

        return $this;
    }

    /**
     * Replace stream's keys by given key-value pairs, where key is a key of stream and value is a new key.
     *
     * @param array $newKeysMap Key-value pairs, where key is a key of stream and value is a new key.
     * @param bool  $skipOnFail Suppress exception if key not found in stream.
     *
     * @return static
     */
    public function remap(array $newKeysMap, bool $skipOnFail = true): static
    {
        return $this->remapBy(function (mixed $value, mixed $index) use ($newKeysMap, $skipOnFail): mixed {
            if (!array_key_exists($index, $newKeysMap)) {
                if ($skipOnFail) {
                    return $index;
                }

                throw new InvalidArgumentException("Key '$index' not found in new keys map.");
            }

            return $newKeysMap[$index];
        });
    }

    /**
     * Replace stream's keys by given key-value pairs, where key is a key of stream and value is a new key.
     * The difference from remap() is that this method is looking on internal elements array
     * as on the array of arrays (list of maps).
     *
     * @param array $newKeysMap Key-value pairs, where key is a key of stream and value is a new key.
     * @param bool  $skipOnFail Suppress exception if key not found in stream.
     *
     * @return static
     */
    public function remapAll(array $newKeysMap, bool $skipOnFail = true): static
    {
        return $this->map(fn(array $row): array => static::of($row)->remap($newKeysMap, $skipOnFail)->toMap());
    }
}