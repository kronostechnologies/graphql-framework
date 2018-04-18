<?php


namespace Kronos\GraphQLFramework\Utils\Reflection;


class ClassInfoReaderResult
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $className;

    /**
     * ClassInfoReaderResult constructor.
     * @param string $namespace
     * @param string $className
     */
    public function __construct($namespace, $className)
    {
        $this->namespace = $namespace;
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

	/**
	 * @return string
	 */
    public function getFQN()
	{
		return $this->namespace . '\\' . $this->className;
	}
}