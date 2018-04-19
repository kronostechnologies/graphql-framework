<?php


namespace Kronos\GraphQLFramework\Resolver\Controller;


use Kronos\GraphQLFramework\Resolver\Controller\Exception\ControllerDirNotFoundException;
use Kronos\GraphQLFramework\Utils\DirectoryLister;
use Kronos\Tests\GraphQLFramework\Utils\Exception\DirectoryNotFoundException;

class ControllerFinder
{
	const CONTROLLER_SUFFIX = 'Controller';

	/**
	 * Directory where to seek the controllers.
	 *
	 * @var string
	 */
	protected $controllersDirectory;

	/**
	 * In-memory cached controllers found.
	 *
	 * @var string[]
	 */
	protected $controllers;

	/**
	 * @param string $controllersDirectory
	 */
	public function __construct($controllersDirectory)
	{
		$this->controllersDirectory = $controllersDirectory;
	}

	/**
	 * Returns a list of the files finishing with Controller.php, which are potentially a controller. It is not
	 * checked here if they are extended by BaseController, so it is not known yet if they really are valid
	 * controllers.
	 *
	 * @return string[]
	 * @throws ControllerDirNotFoundException
	 */
	public function getPotentialControllerClasses()
	{
		if ($this->controllers === null) {
			$lister = new DirectoryLister($this->controllersDirectory);

			try {
				$this->controllers = $lister->getFilesFilteredByRegex($this->getCompatibleControllersRegex());
			} catch (DirectoryNotFoundException $ex) {
				throw new ControllerDirNotFoundException($this->controllersDirectory, $ex);
			}
		}

		return $this->controllers;
	}

	/**
	 * @return string
	 */
	protected function getCompatibleControllersRegex()
	{
		return '/' . self::CONTROLLER_SUFFIX . '\.php/';
	}
}