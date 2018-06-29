<?php


namespace Kronos\Tests\GraphQLFramework\Resolver\Deferred\Store;


use Kronos\GraphQLFramework\Resolver\Deferred\Store\DeferredValueStore;
use PHPUnit\Framework\TestCase;

class DeferredValueStoreTest extends TestCase
{
    /**
     * @var DeferredValueStore
     */
    protected $valueStore;

    public function setUp()
    {
        $this->valueStore = new DeferredValueStore();
    }

    public function test_EmptySet_getGroupIds_ReturnsEmptyArray()
    {
        $actual = $this->valueStore->getGroupIds('TestGroup');

        $this->assertSame([], $actual);
    }

    public function test_SetGroup_getGroupIds_ReturnsAddedGroupId()
    {
        $this->valueStore->addToGroup('TestGroup', 1);
        $actual = $this->valueStore->getGroupIds('TestGroup');

        $this->assertEquals([1], $actual);
    }
}