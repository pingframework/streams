<?php

namespace Pingframework\Streams\Composition;

trait StreamCollectorTrait
{
    use StreamElementsTrait;

    /**
     * Collects elements using given collector function.
     * Returns result of the collector function.
     *
     * @param callable $collector Collector function.
     *
     * @return mixed
     */
    public function collect(callable $collector): mixed
    {
        return call_user_func($collector, $this->elements);
    }
}