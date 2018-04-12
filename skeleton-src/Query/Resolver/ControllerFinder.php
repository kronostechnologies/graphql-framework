<?php

/**
 * Lists the available controllers under a specified directory.
 */
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
     * Namespace for $controllersDirectory.
     *
     * @var string
     */
    protected $controllersNamespace;

    /**
     * In-memory cached controllers found.
     *
     * @var string[]
     */
    protected $controllers;

    /**
     * @param string $controllersDirectory
     * @param string $controllersNamespace
     */
    public function __construct($controllersDirectory, $controllersNamespace)
    {
        $this->controllersDirectory = $controllersDirectory;
        $this->controllersNamespace = $controllersNamespace;
    }

    /**
     * Returns a list of all the available controller classes with their FQN.
     *
     * @return string[]
     */
    public function getAvailableControllerClasses()
    {
        if ($this->controllers === null) {
            $phpFiles = $this->getPHPFilesInControllersDirectory();
            $filteredFiles = $this->getFilteredControllerFiles($phpFiles);

            $this->controllers = $this->getFQNOfControllerFiles($filteredFiles);
        }

        return $this->controllers;
    }


    /**
     * Returns the Full Qualified Namespace of the files instead of their filename, useful
     * for instancing the class.
     *
     * @param string[] $filenames
     *
     * @return string[]
     */
    protected function getFQNOfControllerFiles(array $filenames)
    {

    }

    /**
     * Returns the controller files filtered by name.
     *
     * @param string[] $phpFiles
     *
     * @returns string[]
     */
    protected function getFilteredControllerFiles(array $phpFiles)
    {

    }

    /**
     * Returns a list of PHP files under the specified directory.
     *
     * @return string[]
     */
    protected function getPHPFilesInControllersDirectory()
    {

    }

}