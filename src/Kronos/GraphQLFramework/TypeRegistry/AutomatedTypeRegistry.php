<?php


namespace Kronos\GraphQLFramework\TypeRegistry;

use function array_shift;
use function file_get_contents;
use GraphQL\Type\Definition\Type;
use Kronos\GraphQLFramework\TypeRegistry\Exception\TypeNotFoundException;
use Kronos\GraphQLFramework\Utils\DirectoryLister;
use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReader;

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
	 * @var string[]
	 */
	protected $pendingTypes = [];

	/**
	 * @var bool
	 */
	protected $initialDiscoveryDone = false;

	/*
	 * @var bool
	 */
	protected $inDiscovery = false;

	/**
	 * @param string $typesDirectory
	 */
	public function __construct($typesDirectory)
	{
		$this->typesDirectory = $typesDirectory;
	}

	/**
	 * @return DiscoveredType[]
	 * @throws \Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassNameFoundException
	 */
	protected function getDiscoveredTypes()
	{
		if (!$this->initialDiscoveryDone) {
			$this->inDiscovery = true;

			$dirLister = new DirectoryLister($this->typesDirectory);
			$typeFiles = $dirLister->getFilesFilteredByExtension('php');

			foreach ($typeFiles as $typeFile) {
				$typeFileContent = file_get_contents($typeFile);
				$typeFileClassReader = new ClassInfoReader($typeFileContent);

				$typeFileInfo = $typeFileClassReader->read();
				$typeName = $this->getTypeNameFromClassName($typeFileInfo->getClassName());
				$typeFQN = $typeFileInfo->getFQN();

				if (!in_array($typeName, $this->pendingTypes) && !$this->doesTypeExist($typeName)) {
					$this->pendingTypes[] = $typeName;
					$this->discoveredTypes[] = new DiscoveredType($typeName, new $typeFQN($this, null));
				}
			}

			$this->initialDiscoveryDone = true;
			$this->inDiscovery = false;
		}

		return $this->discoveredTypes;
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
	 * @throws \Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassNameFoundException
	 */
	public function getTypeByName($soughtTypeName)
	{
		$discoveredTypes = $this->getDiscoveredTypes();

		$matchingTypes = array_filter($discoveredTypes, function (DiscoveredType $discoveredType) use ($soughtTypeName) {
			return $discoveredType->getTypeName() === $soughtTypeName;
		});
		/** @var DiscoveredType|bool $matchingType */
		$matchingType = array_shift($matchingTypes);

		if ($matchingType === null) {
			throw new TypeNotFoundException($soughtTypeName);
		}

		return $matchingType->getTypeInstance();
	}

	/**
	 * Returns true if the type exists.
	 * @param string $soughtTypeName
	 * @return bool
	 * @throws \Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassNameFoundException
	 */
	public function doesTypeExist($soughtTypeName)
	{
		if ($this->inDiscovery) {
			$discoveredTypes = $this->discoveredTypes;
		} else {
			$discoveredTypes = $this->getDiscoveredTypes();
		}

		$matchingTypes = array_filter($discoveredTypes, function (DiscoveredType $discoveredType) use ($soughtTypeName) {
			return $discoveredType->getTypeName() === $soughtTypeName;
		});

		return count($matchingTypes) > 0;
	}

	/**
	 * Helper function to fetch query type. Throws an exception if not found as it must always be provided as per the RFC.
	 * @return Type
	 * @throws TypeNotFoundException
	 * @throws \Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassNameFoundException
	 */
	public function getQueryType()
	{
		return $this->getTypeByName('Query');
	}

	/**
	 * Helper function fetch mutation type. Can return null as per the RFC, which means mutations are not supported.
	 * @return Type|null
	 * @throws TypeNotFoundException
	 * @throws \Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassNameFoundException
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