<?php


namespace Kronos\GraphQLFramework;


use Closure;
use DI\ContainerBuilder;
use Kronos\GraphQLFramework\Exception\NoCacheAdapterConfiguredException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class FrameworkConfiguration
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $controllersDirectory;

    /**
     * @var string
     */
    protected $generatedSchemaDirectory;

    /**
     * @var bool
     */
    protected $isDevModeEnabled = false;

    /**
     * @var CacheItemPoolInterface|null
     */
    protected $cacheAdapter;

    /**
     * @var bool|null
     */
    protected $forceRegistryCacheOnOrOff;

    /**
     * @var bool|null
     */
    protected $forceControllersCacheOnOrOff;

    /**
     * @var bool|null
     */
    protected $forceResolverCacheOnOrOff;

    /**
     * @var bool|null
     */
    protected $forceFetchAdapterCacheOnOrOff;

    /**
     * @var ContainerBuilder
     */
    protected $containerBuilder;

    /**
     * @var FrameworkMiddleware[]
     */
    protected $middlewares = [];

	/**
	 * @var Closure|null
	 */
    protected $exceptionHandler;

    /**
     * @var string[]
     */
    protected $forcedAcknowledgedTypes = [];

    public function __construct()
	{
		$this->logger = new NullLogger();
	}

	/**
     * @return FrameworkConfiguration
     */
    public static function create()
    {
        return new self();
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return string
     */
    public function getControllersDirectory()
    {
        return $this->controllersDirectory;
    }

    /**
     * @return string
     */
    public function getGeneratedSchemaDirectory()
    {
        return $this->generatedSchemaDirectory;
    }

    /**
     * @return bool
     */
    public function isDevModeEnabled()
    {
        return $this->isDevModeEnabled;
    }

    /**
     * @return null|CacheItemPoolInterface
     */
    public function getCacheAdapter()
    {
        return $this->cacheAdapter;
    }

    /**
     * @param bool|null $forceStatus
     * @param string $cacheName
     * @return bool
     * @throws NoCacheAdapterConfiguredException
     */
    protected function getPersistentCacheStatus($forceStatus, $cacheName)
    {
        if ($forceStatus === true) {
            if ($this->cacheAdapter === null) {
                throw new NoCacheAdapterConfiguredException($cacheName);
            } else {
                return true;
            }
        } else if ($forceStatus === false) {
            return false;
        } else {
            if ($this->isDevModeEnabled) {
                return false;
            } else if ($this->cacheAdapter === null) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * @return bool
     * @throws NoCacheAdapterConfiguredException
     */
    public function isRegistryCacheEnabled()
    {
        return $this->getPersistentCacheStatus($this->forceRegistryCacheOnOrOff, 'RegistryCache');
    }

    /**
     * @return bool
     * @throws NoCacheAdapterConfiguredException
     */
    public function isControllersCacheEnabled()
    {
        return $this->getPersistentCacheStatus($this->forceControllersCacheOnOrOff, 'ControllersCache');
    }

    /**
     * @param bool|null $forceStatus
     * @return bool
     */
    protected function getEphemeralCacheStatus($forceStatus)
    {
        if ($forceStatus === true || $forceStatus === false) {
            return $forceStatus;
        } else {
            return true;
        }
    }

    /**
     * @return bool
     */
    public function isResolverCacheEnabled()
    {
        return $this->getEphemeralCacheStatus($this->forceResolverCacheOnOrOff);
    }

    /**
     * @return bool
     */
    public function isFetchAdapterCacheEnabled()
    {
        return $this->getEphemeralCacheStatus($this->forceFetchAdapterCacheOnOrOff);
    }

    /**
     * @return FrameworkConfiguration
     */
    public function enableDevMode()
    {
        $this->isDevModeEnabled = true;
        return $this;
    }

    /**
     * @return FrameworkConfiguration
     */
    public function disableDevMode()
    {
        $this->isDevModeEnabled = false;
        return $this;
    }

    /**
     * @return FrameworkConfiguration
     */
    public function forceRegistryCacheOn()
    {
        $this->forceRegistryCacheOnOrOff = true;
        return $this;
    }

    /**
     * @return FrameworkConfiguration
     */
    public function forceRegistryCacheOff()
    {
        $this->forceRegistryCacheOnOrOff = false;
        return $this;
    }

    /**
     * @return FrameworkConfiguration
     */
    public function forceControllersCacheOn()
    {
        $this->forceControllersCacheOnOrOff = true;
        return $this;
    }

    /**
     * @return FrameworkConfiguration
     */
    public function forceControllersCacheOff()
    {
        $this->forceControllersCacheOnOrOff = false;
        return $this;
    }

    /**
     * @return FrameworkConfiguration
     */
    public function forceResolverCacheOn()
    {
        $this->forceResolverCacheOnOrOff = true;
        return $this;
    }

    /**
     * @return FrameworkConfiguration
     */
    public function forceResolverCacheOff()
    {
        $this->forceResolverCacheOnOrOff = false;
        return $this;
    }

    /**
     * @return FrameworkConfiguration
     */
    public function forceFetchAdapterCacheOn()
    {
        $this->forceFetchAdapterCacheOnOrOff = true;
        return $this;
    }

    /**
     * @return FrameworkConfiguration
     */
    public function forceFetchAdapterCacheOff()
    {
        $this->forceFetchAdapterCacheOnOrOff = false;
        return $this;
    }

    /**
     * @param null|CacheItemPoolInterface $cacheAdapter
     * @return FrameworkConfiguration
     */
    public function setCacheAdapter(CacheItemPoolInterface $cacheAdapter = null)
    {
        $this->cacheAdapter = $cacheAdapter;
        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return FrameworkConfiguration
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param string $controllersDirectory
     * @return FrameworkConfiguration
     */
    public function setControllersDirectory($controllersDirectory)
    {
        $this->controllersDirectory = $controllersDirectory;
        return $this;
    }

    /**
     * @param string $generatedSchemaDirectory
     * @return FrameworkConfiguration
     */
    public function setGeneratedSchemaDirectory($generatedSchemaDirectory)
    {
        $this->generatedSchemaDirectory = $generatedSchemaDirectory;
        return $this;
    }

    /**
     * @param FrameworkMiddleware $middleware
     * @return $this
     */
    public function addMiddleware(FrameworkMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * @param FrameworkMiddleware $middleware
     * @return $this
     */
    public function removeMiddleware(FrameworkMiddleware $middleware)
    {
        $this->middlewares = array_filter($this->middlewares, function(FrameworkMiddleware $registeredMiddleware) use ($middleware) {
            return $registeredMiddleware !== $middleware;
        });
        return $this;
    }

    /**
     * @return FrameworkMiddleware[]
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

	/**
	 * @return Closure|null
	 */
	public function getExceptionHandler()
	{
		return $this->exceptionHandler;
	}

	/**
	 * @param Closure|null $exceptionHandler ($exception) => void
	 * @return FrameworkConfiguration
	 */
	public function setExceptionHandler(Closure $exceptionHandler)
	{
		$this->exceptionHandler = $exceptionHandler;
		return $this;
	}

    /**
     * Gets the container builder. The container is built once the application framework is initialized. Dependency
     * injection can be used at the controller level.
     *
     * @return ContainerBuilder
     */
	public function getContainerBuilder()
    {
        if (!$this->containerBuilder) {
            $this->containerBuilder = new ContainerBuilder();
        }

        return $this->containerBuilder;
    }

    /**
     * Some types are not recognized by the underlying GraphQL library automatically.
     * This usually happens with interface implementations. Register them here.
     *
     * @param string $type
     * @return self
     */
    public function addForcedAcknowledgeType($type)
    {
        $this->forcedAcknowledgedTypes[] = $type;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getForcedAcknowledgedTypes()
    {
        return $this->forcedAcknowledgedTypes;
    }
}
