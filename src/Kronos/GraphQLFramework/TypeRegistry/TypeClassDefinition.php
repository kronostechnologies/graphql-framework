<?php


namespace Kronos\GraphQLFramework\TypeRegistry;


class TypeClassDefinition
{
    /**
     * @var string
     */
    protected $typeName;

    /**
     * @var string
     */
    protected $classFQN;

    /**
     * @param string $typeName
     * @param string $classFQN
     */
    public function __construct($typeName, $classFQN)
    {
        $this->typeName = $typeName;
        $this->classFQN = $classFQN;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @return string
     */
    public function getClassFQN()
    {
        return $this->classFQN;
    }
}