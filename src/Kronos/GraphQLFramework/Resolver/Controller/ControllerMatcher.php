<?php


namespace Kronos\GraphQLFramework\Resolver\Controller;

use Kronos\GraphQLFramework\Resolver\Controller\Exception\NoMatchingControllerFoundException;
use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReaderResult;
use function preg_match;
use function strtolower;

/**
 * Matches a controller from a ClassInfoReaderResult to a type.
 */
class ControllerMatcher
{
	/**
	 * @var ClassInfoReaderResult[]
	 */
	protected $knownControllers;

	/**
	 * @param ClassInfoReaderResult[] $knownControllers
	 */
	public function __construct(array $knownControllers)
	{
		$this->knownControllers = $knownControllers;
	}

	/**
	 * Returns the controller information for the given type name. Returns null if none was found.
	 *
	 * @param string $typeName
	 * @return ClassInfoReaderResult|null
	 */
	public function getControllerForTypeName($typeName)
	{
		$typeNameLower = trim(strtolower($typeName));
		foreach ($this->knownControllers as $knownController) {
			$controllerTypeName = $this->getControllerTypeName($knownController->getClassName());

			if ($controllerTypeName === $typeNameLower) {
				return $knownController;
			}
		}

		return null;
	}

	/**
	 * @param string $controllerClassName
	 * @return string
	 */
	protected function getControllerTypeName($controllerClassName)
	{
		$matches = [];

		preg_match("/(.*)Controller/", $controllerClassName, $matches);

		return strtolower($matches[1]);
	}
}