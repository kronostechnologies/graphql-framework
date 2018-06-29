<?php


namespace Kronos\GraphQLFramework\Resolver\Deferred\Store;


class DeferredValueGroup
{
    /**
     * @var string
     */
    protected $typeGroupName;

    /**
     * @var int[]
     */
    protected $ids = [];

    /**
     * DeferredValueGroup constructor.
     * @param string $typeGroupName
     */
    public function __construct($typeGroupName)
    {
        $this->typeGroupName = $typeGroupName;
    }

    /**
     * @return string
     */
    public function getTypeGroupName()
    {
        return $this->typeGroupName;
    }

    /**
     * @param int $id
     */
    public function appendId($id)
    {
        if (!in_array($id, $this->ids)) {
            $this->ids[] = $id;
        }
    }

    /**
     * @return int[]
     */
    public function getIds()
    {
        return $this->ids;
    }
}