<?php


namespace Kronos\GraphQLFramework\Resolver\Controller;


use Kronos\GraphQLFramework\Resolver\Controller\Exception\ControllerDirNotFoundException;
use Kronos\GraphQLFramework\Resolver\Exception\DirectoryNotFoundException;
use Kronos\GraphQLFramework\Utils\DirectoryLister;
use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReader;
use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReaderResult;
use Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassNameFoundException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use function file_get_contents;

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
	 * @var ClassInfoReaderResult[]
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
	 * @return ClassInfoReaderResult[]
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
					$controllerFQNs[] = $this->getClassResultFromFilename($controllerFilename);
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
	 * @param string $filename
	 * @return ClassInfoReaderResult
	 * @throws NoClassNameFoundException
	 */
	protected function getClassResultFromFilename($filename)
	{
		$fileContent = file_get_contents($filename);

		$classInfo = new ClassInfoReader($fileContent);

		return $classInfo->read();
	}

	/**
	 * @return string
	 */
	protected function getCompatibleControllersRegex()
	{
		return '/' . self::CONTROLLER_SUFFIX . '\.php/';
	}
}