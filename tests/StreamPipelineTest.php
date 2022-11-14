<?php

namespace Pingframework\Streams\Tests;

use PhpOption\Option;
use Pingframework\Streams\StreamPipeline;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class StreamPipelineTest extends TestCase
{
    /**
     * @test
     */
    public function definePipeline_invokeItOnSomeArrays_itShouldProceedInput()
    {
        //given

        $pipeline = StreamPipeline::forIterable()
            ->map(fn($value) => strtoupper($value))
            ->filter(fn($value) => $value[0] === 'A')
            ->unique()
            ->toList();

        //when

        $actual = $pipeline(['Asia', 'Apple', 'php', 'android', 'Android', 'tv', 'php']);

        //then

        $this->assertSame(['ASIA', 'APPLE', 'ANDROID'], $actual);
    }

    /**
     * @test
     */
    public function definePipelineAsVarargs_everyArgumentShouldBeOneElementOfArray()
    {
        //given

        $pipeline = StreamPipeline::forVarargs()
            ->map(fn($value) => strtoupper($value))
            ->toList();

        //when

        $actual = $pipeline('a', 'b', 'C');

        //then

        $this->assertSame(array('A', 'B', 'C'), $actual);
    }

    /**
     * @test
     */
    public function definePipelineWithOneSingleValueArg_acceptsOnlyOneArg()
    {
        //given

        $composer = StreamPipeline::forValue()
            ->map(fn($value) => strtoupper($value))
            ->toList();

        //when

        $actual = $composer('a', 'b', 'C');

        //then

        $this->assertSame(['A'], $actual);
    }

    /**
     * @test
     *
     */
    public function definePipeline_executeTwoTerminalOperations_throwException()
    {
        $this->expectException(RuntimeException::class);
        StreamPipeline::forIterable()
            ->toList()
            ->toMap();
    }

    /**
     * @test
     *
     */
    public function definePipeline_executeIntermediateOpAfterTerminalOp_throwException()
    {
        $this->expectException(RuntimeException::class);
        StreamPipeline::forIterable()
            ->toList()
            ->map(fn($value) => strtolower($value));
    }

    /**
     * @test
     */
    public function definePipeline_executeTerminalOpWithOptionAsResult()
    {
        $max = StreamPipeline::forIterable();
        $max
            ->max()
            ->map(fn($value) => 'max: '.$value)
            ->orElse(Option::fromValue('max not found'))
            ->get();

        $this->assertEquals('max: 5', $max([1, 5, 3]));
        $this->assertEquals('max not found', $max([]));
    }
}
