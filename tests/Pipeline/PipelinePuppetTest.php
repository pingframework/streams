<?php

namespace Pingframework\Streams\Tests\Pipeline;

use BadMethodCallException;
use Pingframework\Streams\Pipeline\PipelinePuppet;
use PHPUnit\Framework\TestCase;
use Pingframework\Streams\Tests\Stub\Book;
use Pingframework\Streams\Tests\Stub\BookBuilder;
use Pingframework\Streams\Tests\Stub\Publisher;

class PipelinePuppetTest extends TestCase
{
    /**
     * @test
     */
    public function givenEmptyPuppet_doNothing()
    {
        //given

        $puppet = new PipelinePuppet();

        $book = BookBuilder::create()
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertSame($book, $actual);
    }

    /**
     * @test
     */
    public function givenSingleMethodCall_expectThisCallOnObject()
    {
        //given

        $puppet = PipelinePuppet::record()->getPublisher();

        $book = BookBuilder::create()
            ->publisher("O'rly")
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertSame($book->getPublisher(), $actual);
    }

    /**
     * @test
     */
    public function givenSinglePropertyAccess_expectAccessThisProperty()
    {
        //given

        $puppet = PipelinePuppet::record()->name;

        $publisher = new Publisher('adison');

        //when

        $actual = $puppet->play($publisher);

        //then

        $this->assertSame($publisher->name, $actual);
    }

    /**
     * @test
     */
    public function givenChainedPropertyAccessAndMethodCall_dontWorry_beHappy()
    {
        //given

        $puppet = PipelinePuppet::record()->getPublisher()->name;

        $book = BookBuilder::create()
            ->publisher('halion')
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertSame($book->getPublisher()->name, $actual);
    }

    /**
     * @test
     */
    public function givenMethodCallWithArgument_yesThisAlsoWorks()
    {
        //given

        $puppet = PipelinePuppet::record()->getTitle(Book::SHORT);

        $book = BookBuilder::create()
            ->title('title')
            ->shortTitle('short title')
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertSame('short title', $actual);
    }

    /**
     * @test
     */
    public function givenChainedMethodCalls_secondCallIsOnNull_nullShouldBeReturned()
    {
        //given

        $puppet = PipelinePuppet::record()->getPublisher()->getName();

        $book = BookBuilder::create()
            ->publisher(null)
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertNull($actual);
    }

    /**
     * @test
     */
    public function givenMethodCall_methodReturnsFalse_falseShouldBeReturned()
    {
        //given

        $puppet = PipelinePuppet::record()->isCool();

        $book = BookBuilder::create()
            ->cool(false)
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertFalse($actual);
    }

    /**
     * @test
     */
    public function givenPropertyAndArrayAccess_yesArrayAccessShouldWorkPerfectly()
    {
        //given

        $puppet = PipelinePuppet::record()->authors[1]->getName();

        $book = BookBuilder::create()
            ->author('Eddy')
            ->author('psliwa')
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertEquals($book->authors[1]->getName(), $actual);
    }

    /**
     * @test
     */
    public function givenPropertyAndArrayAccess_offsetDoesNotExist_ooohReturnNull()
    {
        //given

        $puppet = PipelinePuppet::record()->authors[1]->getName();

        $book = BookBuilder::create()
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertNull($actual);
    }

    /**
     * @test
     */
    public function givenUnexistedMethodCall_itsToMuch_throwException()
    {
        //given

        $puppet = PipelinePuppet::record()->unexistedMethod();

        $book = BookBuilder::create()
            ->getBook();

        //when

        $this->expectException(BadMethodCallException::class);
        $puppet($book);
    }
}
