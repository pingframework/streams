<?php

namespace Pingframework\Streams\Pipeline;

enum PipelineTypeEnum
{
    case Iterable;
    case Varargs;
    case Value;
}
