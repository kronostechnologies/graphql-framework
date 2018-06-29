<?php


namespace Kronos\Tests\GraphQLFramework\Resolver\Deferred;


use Kronos\GraphQLFramework\Resolver\Deferred\Deferrer;
use Kronos\GraphQLFramework\Resolver\Deferred\Store\DeferredValue;
use PHPUnit\Framework\TestCase;

class DeferredTest extends TestCase
{
    /**
     * @var Deferrer
     */
    protected $deferrer;

    public function setUp()
    {
        $this->deferrer = new Deferrer();
    }

    public function test_defer_CreatesInstanceOfDeferredValue()
    {
        $actual = $this->deferrer->defer('TestType', 1);

        $this->assertInstanceOf(DeferredValue::class, $actual);
    }

    public function test_SetTypeAndId_defer_CreatedDeferredValueContainsType()
    {
        $actual = $this->deferrer->defer('TestType', 1);

        $this->assertSame('TestType', $actual->getTypeName());
    }

    public function test_SetTypeAndId_defer_CreatedDeferredValueContainsId()
    {
        $actual = $this->deferrer->defer('TestType', 1);

        $this->assertSame(1, $actual->getAttributedId());
    }
}