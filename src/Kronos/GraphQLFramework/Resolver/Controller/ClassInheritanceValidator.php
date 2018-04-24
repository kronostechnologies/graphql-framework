<?php


namespace Kronos\GraphQLFramework\Resolver\Controller;

use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReaderResult;
use ReflectionClass;

/**
 * Makes sure that whichever class we are looking at inherits the concerned class in any way.
 */
class ClassInheritanceValidator
{
	protected $baseControllerFQN;

	/**
	 * @param string $baseControllerFQN
	 */
	public function __construct($baseControllerFQN)
	{
		$this->baseControllerFQN = $baseControllerFQN;
	}

	/**
	 * @param ClassInfoReaderResult[] $controllers
	 * @return ClassInfoReaderResult[]
	 */
	public function getPertinentControllers(array $controllers)
	{
		return array_filter($controllers, function (ClassInfoReaderResult $controller) {
			return $this->isControllerPertinent($controller->getFQN());
		});
	}

	/**
	 * @param string $controllerFQN
	 * @return bool
	 * @throws \ReflectionException
	 */
	public function isControllerPertinent($controllerFQN)
	{
		return $this->doesFQNInherits($controllerFQN, $this->baseControllerFQN);
	}

	/**
	 * Recursively fetches the parent class until it arrives at the root one, which should be BaseController.
	 * @param string $currentFQN
	 * @param string $inherits
	 * @return string
	 * @throws \ReflectionException
	 */
	protected function doesFQNInherits($currentFQN, $inherits)
	{
		$refClass = new ReflectionClass($currentFQN);

		$parentClass = $refClass->getParentClass();

		if ($parentClass !== false) {
			if ($parentClass->getName() === $inherits) {
				return true;
			} else {
				return $this->doesFQNInherits($parentClass->getName(), $inherits);
			}
		} else {
			return false;
		}
	}
}