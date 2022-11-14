<?php

namespace Pingframework\Streams\Composition;

use ArrayObject;

trait StreamConvertorTrait
{
    use StreamElementsTrait;

    /**
     * Returns list of elements - indexes are ignored, all values are re-indexed.
     * Use this function when indexes in use context have not meaning.
     *
     * @return array
     */
    public function toList(): array
    {
        return array_values($this->elements);
    }

    /**
     * Returns array of elements - indexes are preserved.
     * Use this function when indexes in use context have meaning.
     *
     * @return array
     */
    public function toMap(): array
    {
        return $this->elements;
    }


    /**
     * Maps internal key-value array to passed object and returns it back,
     * where key is an object's property and value is a object's property value.
     *
     * @param object $object
     *
     * @return object
     */
    public function toObject(object $object): object
    {
        foreach ($this->elements as $key => $value) {
            $object->{$key} = $value;
        }

        return $object;
    }

    /**
     * Converts stream to array object.
     * If $object is not passed, then new ArrayObject instance will be created.
     * If $object is passed, then it will be filled with internal array, all existing keys will be overwritten.
     *
     * @param ArrayObject|null $object ArrayObject instance.
     *
     * @return object
     */
    public function toArrayObject(?ArrayObject $object = null): object
    {
        if ($object === null) {
            return new ArrayObject($this->elements);
        }

        foreach (array_merge($object->getArrayCopy(), $this->elements) as $k => $v) {
            $object->offsetSet($k, $v);
        }
        return $object;
    }
}