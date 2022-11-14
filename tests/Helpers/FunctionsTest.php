<?php

namespace Pingframework\Streams\Tests\Helpers;

use PhpOption\None;
use PhpOption\Some;
use Pingframework\Streams\Helpers\Functions;
use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider functionsProvider
     */
    public function testFunctions($func, $argument, $expectedValue)
    {
        $this->assertEquals($expectedValue, $func($argument));
    }

    public function functionsProvider()
    {
        return array(
            //it uses PropertyGetter, so more detailed tests are unnecessary
            array(Functions::getPropertyValue('name'), array('name' => 'Piotr'), 'Piotr'),
            array(Functions::getPropertyOptionValue('name'), array('name' => 'Piotr'), Some::create('Piotr')),
            array(Functions::getPropertyOptionValue('unexisted'), array('name' => 'Piotr'), None::create()),
            array(Functions::getPropertyOptionValue('existedButFalse'), array('existedButFalse' => false), Some::create(false)),
            array(Functions::getPropertyOptionValue('existedButFalse', false), array('existedButFalse' => false), None::create()),
        );
    }

    public function testOneArgFunction()
    {
        $argsNum = null;

        $innerFunction = function() use(&$argsNum){
            $argsNum = func_num_args();
        };

        $outerFunction = Functions::oneArgumentFunction($innerFunction);

        $outerFunction(1, 2, 3, 4, 5);

        $this->assertSame(1, $argsNum);
    }

    public function testCount()
    {
        $count = Functions::count('values');

        $this->assertEquals(3, $count(array('values' => array(1, 2, 3))));
    }
}
