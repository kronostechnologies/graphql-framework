<?php


namespace Kronos\GraphQLFramework\Resolver\Controller;

use Kronos\Tests\GraphQLFramework\BaseController;
use ReflectionClass;

/**
 * Makes sure that whichever class we are looking at inherits BaseController in any way.
 */
class ControllerPertinenceChecker
{
	const BASE_CONTROLLER_FQN = BaseController::class;

	/**
	 * @param string $controllerFQN
	 * @return bool
	 * @throws \ReflectionException
	 */
	public function isControllerPertinent($controllerFQN)
	{
		 return $this->doesFQNInherits($controllerFQN, self::BASE_CONTROLLER_FQN);
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