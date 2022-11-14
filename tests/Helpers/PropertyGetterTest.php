<?php

namespace Pingframework\Streams\Tests\Helpers;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use Pingframework\Streams\Helpers\is;
use Pingframework\Streams\Helpers\PropertyGetter;
use Pingframework\Streams\Pipeline\PipelinePuppet;
use Pingframework\Streams\Stream;

class PropertyGetterTest extends TestCase
{
    /**
     * @var PropertyGetter
     */
    private PropertyGetter $propertyGetter;

    protected function setUp(): void
    {
        $this->propertyGetter = new PropertyGetter();
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function testGetValue($object, $property, $expectedValue)
    {
        $this->assertEquals($expectedValue, $this->propertyGetter->getValue($object, $property));
    }

    /**
     * @test
     */
    public function testGetFewTimesValues()
    {
        //given

        $object = new PropertyGetterTest_Object('p1', 'p2');

        //when

        $values = array(
            $this->propertyGetter->getValue($object, 'property1'),
            $this->propertyGetter->getValue($object, 'property1'),
        );

        //then

        $this->assertTrue(
            Stream::of($values)
                ->allMatch(is::eq('p1'))
        );
    }

    public function dataProvider(): array
    {
        return array(
            array(
                new PropertyGetterTest_Object('p1', 'p2'),
                'property1',
                'p1',
            ),
            array(
                new PropertyGetterTest_Object('p1', 'p2'),
                'property2',
                'p2',
            ),
            array(
                array('prop1' => 'p1'),
                'prop1',
                'p1',
            ),
            array(
                array('prop1' => 'p1'),
                'prop2',
                null,
            ),
            array(
                new PropertyGetterTest_Object(array('nestedProp' => 'value'), 'p2'),
                'property1.nestedProp',
                'value',
            ),
            array(
                new PropertyGetterTest_Object('p1', 'p2'),
                PipelinePuppet::object()->getProperty2(),
                'p2',
            ),
            array(
                new ArrayObject(array('name' => 'value')),
                'name',
                'value',
            ),
            //"key" is a function
            array(
                array('key' => 'value'),
                'key',
                'value'
            )
        );
    }
}

class PropertyGetterTest_Object
{
    public  mixed $property1;
    private mixed $property2;

    function __construct(mixed $property1, mixed $property2)
    {
        $this->property1 = $property1;
        $this->property2 = $property2;
    }

    public function getProperty2(): mixed
    {
        return $this->property2;
    }
}