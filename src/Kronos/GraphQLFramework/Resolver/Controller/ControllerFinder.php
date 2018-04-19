<?php


namespace Kronos\GraphQLFramework\Resolver\Controller;


use function file_get_contents;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\ControllerDirNotFoundException;
use Kronos\GraphQLFramework\Utils\DirectoryLister;
use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReader;
use Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassNameFoundException;
use Kronos\Tests\GraphQLFramework\Utils\Exception\DirectoryNotFoundException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

class ControllerFinder implements LoggerAwareInterface
{
	use LoggerAwareTrait;

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
	 * @param LoggerInterface $logger
	 */
	public function __construct($controllersDirectory, LoggerInterface $logger)
	{
		$this->controllersDirectory = $controllersDirectory;
		$this->logger = $logger;
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
				$controllerFilenames = $lister->getFilesFilteredByRegex($this->getCompatibleControllersRegex());
			} catch (DirectoryNotFoundException $ex) {
				throw new ControllerDirNotFoundException($this->controllersDirectory, $ex);
			}

			$controllerFQNs = [];
			foreach ($controllerFilenames as $controllerFilename) {
				try {
					$controllerFQNs[] = $this->getControllerFQNFromFilename($controllerFilename);
				} catch (NoClassNameFoundException $ex) {
					$this->logger->warning("The class name is missing for the controller located at {$controllerFilename}");
				}
			}

			$this->controllers = $controllerFQNs;
		}

		return $this->controllers;
	}

	/**
	 * While calling getPotentialControllerClasses, we expect the FQNs not the filenames.
	 *
	 * @return string
	 * @throws \Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassNameFoundException
	 */
	protected function getControllerFQNFromFilename($filename)
	{
		$fileContent = file_get_contents($filename);

		$classInfo = new ClassInfoReader($fileContent);
		$result = $classInfo->read();

		return $result->getFQN();
	}

	/**
	 * @return string
	 */
	protected function getCompatibleControllersRegex()
	{
		return '/' . self::CONTROLLER_SUFFIX . '\.php/';
	}
}