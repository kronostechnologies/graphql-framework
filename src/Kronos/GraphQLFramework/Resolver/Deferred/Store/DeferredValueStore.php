<?php


namespace Kronos\GraphQLFramework\Resolver\Deferred\Store;


class DeferredValueStore
{
    /**
     * @var DeferredValueGroup[]
     */
    protected $groups = [];

    /**
     * @param string $groupTypeName
     * @param int $id
     */
    public function addToGroup($groupTypeName, $id)
    {
        $this->getGroupByTypeName($groupTypeName)->appendId($id);
    }

    /**
     * @param string $groupTypeName
     * @return int[]
     */
    public function getGroupIds($groupTypeName)
    {
        return $this->getGroupByTypeName($groupTypeName)->getIds();
    }

    /**
     * @param string $groupTypeName
     * @return DeferredValueGroup
     */
    protected function getGroupByTypeName($groupTypeName)
    {
        foreach ($this->groups as $group) {
            if ($group->getTypeGroupName() === $groupTypeName) {
                return $group;
            }
        }

        $newGroup = new DeferredValueGroup($groupTypeName);
        $this->groups[] = $newGroup;

        return $newGroup;
    }
}