<?php


namespace Kronos\GraphQLFramework\Resolver\Deferred\Store;


class DeferredValue
{
    /**
     * @var string
     */
    protected $typeName;

    /**
     * @var int
     */
    protected $attributedId;

    /**
     * DeferredValue constructor.
     * @param string $typeName
     * @param int $attributedId
     */
    public function __construct($typeName, $attributedId)
    {
        $this->typeName = $typeName;
        $this->attributedId = $attributedId;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @return int
     */
    public function getAttributedId()
    {
        return $this->attributedId;
    }
}