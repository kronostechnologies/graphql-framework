<?php


namespace Kronos\Tests\GraphQLFramework\Resolver\Deferred\Extractor;


use Kronos\GraphQLFramework\Resolver\Deferred\Extractor\StorePopulator;
use Kronos\GraphQLFramework\Resolver\Deferred\Store\DeferredValue;
use Kronos\GraphQLFramework\Resolver\Deferred\Store\DeferredValueStore;
use PHPUnit\Framework\TestCase;

class StorePopulatorTest extends TestCase
{
    /**
     * @var DeferredValueStore
     */
    protected $store;

    /**
     * @var StorePopulator
     */
    protected $populator;

    public function setUp()
    {
        $this->store = new DeferredValueStore();
        $this->populator = new StorePopulator($this->store);
    }

    public function test_Null_populate_StoreRemainsEmpty()
    {
        $this->populator->populate(null);

        $actualGroups = $this->populator->getStore()->getGroups();

        $this->assertSame([], $actualGroups);
    }

    public function test_EmptyArray_populate_StoreRemainsEmpty()
    {
        $this->populator->populate([]);

        $actualGroups = $this->populator->getStore()->getGroups();

        $this->assertSame([], $actualGroups);
    }

    public function test_EmptyStdClass_populate_StoreRemainsEmpty()
    {
        $this->populator->populate(new \stdClass());

        $actualGroups = $this->populator->getStore()->getGroups();

        $this->assertSame([], $actualGroups);
    }

    public function test_StdClassWithSingleTypeEntry_populate_StoreContainsGivenIds()
    {
        $groupName = 'TestDTO';
        $fieldId = 1;
        $given = new \stdClass();
        $given->deferredField = new DeferredValue($groupName, $fieldId);

        $this->populator->populate($given);

        $actualIds = $this->populator->getStore()->getGroupIds($groupName);

        $this->assertEquals([$fieldId], $actualIds);
    }

    public function test_StdClassWithSingleType2Entries_populate_StoreContainsGivenIds()
    {
        $groupName = 'TestDTO';
        $fieldId1 = 1;
        $fieldId2 = 2;
        $given = new \stdClass();
        $given->deferredField1 = new DeferredValue($groupName, $fieldId1);
        $given->deferredField2 = new DeferredValue($groupName, $fieldId2);

        $this->populator->populate($given);

        $actualIds = $this->populator->getStore()->getGroupIds($groupName);

        $this->assertEquals([$fieldId1, $fieldId2], $actualIds);
    }

    public function test_StdClassWithTwoType1Entry_populate_StoreContainsGivenIds()
    {
        $group1Name = 'TestDTO';
        $group1FieldId = 1;
        $group2Name = 'TestSecondDTO';
        $group2FieldId = 20;
        $given = new \stdClass();
        $given->deferredField1 = new DeferredValue($group1Name, $group1FieldId);
        $given->deferredField2 = new DeferredValue($group2Name, $group2FieldId);

        $this->populator->populate($given);

        $actualIdsGroup1 = $this->populator->getStore()->getGroupIds($group1Name);
        $this->assertEquals([$group1FieldId], $actualIdsGroup1);

        $actualIdsGroup2 = $this->populator->getStore()->getGroupIds($group2Name);
        $this->assertEquals([$group2FieldId], $actualIdsGroup2);
    }

    public function test_StdClassUnderlyingEntity_populate_StoreContainsGivenIds()
    {
        $groupName = 'TestDTO';
        $groupFieldId = 1;
        $given = new \stdClass();
        $given->inner = new \stdClass();
        $given->inner->deferredField = new DeferredValue($groupName, $groupFieldId);

        $this->populator->populate($given);

        $actualIdsGroup = $this->populator->getStore()->getGroupIds($groupName);
        $this->assertEquals([$groupFieldId], $actualIdsGroup);
    }
}