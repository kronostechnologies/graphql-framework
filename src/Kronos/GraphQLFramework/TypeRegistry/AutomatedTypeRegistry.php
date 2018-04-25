<?php


namespace Kronos\GraphQLFramework\TypeRegistry;

use GraphQL\Type\Definition\Type;
use Kronos\GraphQLFramework\TypeRegistry\Exception\InternalSchemaException;
use Kronos\GraphQLFramework\TypeRegistry\Exception\TypeNotFoundException;
use Kronos\GraphQLFramework\Utils\DirectoryLister;
use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReader;
use Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassNameFoundException;
use function array_shift;
use function file_get_contents;

/**
 * Auto-detects types from the specified directory.
 */
class AutomatedTypeRegistry
{
	/**
	 * @var string
	 */
	protected $typesDirectory;

	/**
	 * @var DiscoveredType[]
	 */
	protected $discoveredTypes = [];

    /**
     * @var TypeClassDefinition[]
     */
	protected $typeClassesDefinitions;

	/**
	 * @param string $typesDirectory
	 */
	public function __construct($typesDirectory)
	{
		$this->typesDirectory = $typesDirectory;
	}

	/**
	 * @return TypeClassDefinition[]
	 * @throws NoClassNameFoundException
	 * @throws \Kronos\GraphQLFramework\Resolver\Exception\DirectoryNotFoundException
	 */
	protected function getTypeClassesDefinitions()
    {
        if ($this->typeClassesDefinitions === null) {
            $this->typeClassesDefinitions = [];

            $dirLister = new DirectoryLister($this->typesDirectory);
            $typeFiles = $dirLister->getFilesFilteredByExtension('php');

            foreach ($typeFiles as $typeFile) {
                $typeFileContent = file_get_contents($typeFile);
                $typeFileClassReader = new ClassInfoReader($typeFileContent);

                $typeFileInfo = $typeFileClassReader->read();
                $typeName = $this->getTypeNameFromClassName($typeFileInfo->getClassName());
                $typeFQN = $typeFileInfo->getFQN();

                $this->typeClassesDefinitions[] = new TypeClassDefinition($typeName, $typeFQN);
            }
        }

        return $this->typeClassesDefinitions;
    }

	/**
	 * @param $typeName
	 * @return DiscoveredType
	 * @throws InternalSchemaException
	 * @throws NoClassNameFoundException
	 * @throws TypeNotFoundException
	 * @throws \Kronos\GraphQLFramework\Resolver\Exception\DirectoryNotFoundException
	 */
    protected function getDiscoveredType($typeName)
    {
        $similarDiscoveredType = array_filter($this->discoveredTypes, function (DiscoveredType $discoveredType) use ($typeName) {
            return $discoveredType->getTypeName() === $typeName;
        });

        if (count($similarDiscoveredType) > 0) {
            $discoveredType = array_shift($similarDiscoveredType);
        } else {
            $typeClassDefinition = $this->getTypeClassesDefinitions();
            $typeClassDefinition = array_filter($typeClassDefinition, function (TypeClassDefinition $typeClassDefinition) use ($typeName) {
                return $typeClassDefinition->getTypeName() === $typeName;
            });
            $typeClassDefinition = array_shift($typeClassDefinition);

            if ($typeClassDefinition === null) {
                throw new TypeNotFoundException($typeName);
            }

            $typeFQN = $typeClassDefinition->getClassFQN();

            try {
                $typeInstance = new $typeFQN($this, null);
            } catch (\Throwable $ex) {
                throw new InternalSchemaException($typeName, $ex->getMessage(), $ex);
            }

            $discoveredType = new DiscoveredType($typeName, $typeInstance);
            $this->discoveredTypes[] = $discoveredType;
        }

        return $discoveredType;
    }

	/**
	 * @param string $className
	 * @return string
	 */
	protected function getTypeNameFromClassName($className)
	{
		$matches = [];
		preg_match("/(.*)Type/", $className, $matches);

		return $matches[1];
	}

	/**
	 * Get type by name. Throws an exception if the type was not found.
	 * @param string $soughtTypeName
	 * @return Type
	 * @throws TypeNotFoundException
	 * @throws NoClassNameFoundException
	 * @throws InternalSchemaException
	 * @throws \Kronos\GraphQLFramework\Resolver\Exception\DirectoryNotFoundException
	 */
	public function getTypeByName($soughtTypeName)
	{
	    return $this->getDiscoveredType($soughtTypeName)->getTypeInstance();
	}

	/**
	 * Returns true if the type exists.
	 * @param string $soughtTypeName
	 * @return bool
	 * @throws NoClassNameFoundException
	 * @throws \Kronos\GraphQLFramework\Resolver\Exception\DirectoryNotFoundException
	 */
	public function doesTypeExist($soughtTypeName)
	{
        $typeClassesDefinitions = $this->getTypeClassesDefinitions();

		$matchingTypes = array_filter($typeClassesDefinitions, function (TypeClassDefinition $typeClassDefinition) use ($soughtTypeName) {
			return $typeClassDefinition->getTypeName() === $soughtTypeName;
		});

		return count($matchingTypes) > 0;
	}

	/**
	 * Helper function to fetch query type. Throws an exception if not found as it must always be provided as per the RFC.
	 * @return Type
	 * @throws TypeNotFoundException
	 * @throws NoClassNameFoundException
	 * @throws InternalSchemaException
	 * @throws \Kronos\GraphQLFramework\Resolver\Exception\DirectoryNotFoundException
	 */
	public function getQueryType()
	{
		return $this->getTypeByName('Query');
	}

	/**
	 * Helper function fetch mutation type. Can return null as per the RFC, which means mutations are not supported.
	 * @return Type|null
	 * @throws NoClassNameFoundException
	 * @throws InternalSchemaException
	 * @throws TypeNotFoundException
	 * @throws \Kronos\GraphQLFramework\Resolver\Exception\DirectoryNotFoundException
	 */
	public function getMutationType()
	{
		if ($this->doesTypeExist('Mutation')) {
			return $this->getTypeByName('Mutation');
		} else {
			return null;
		}
	}
}