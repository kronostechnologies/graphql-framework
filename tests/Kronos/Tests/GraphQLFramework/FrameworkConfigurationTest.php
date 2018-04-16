<?php


namespace Kronos\Tests\GraphQLFramework;


use Kronos\GraphQLFramework\Exception\NoCacheAdapterConfiguredException;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;

class FrameworkConfigurationTest extends TestCase
{
    /**
     * @var MockObject|CacheItemPoolInterface
     */
    protected $mockedCacheAdapter;

    public function setUp()
    {
        $this->mockedCacheAdapter = $this->createMock(CacheItemPoolInterface::class);
    }

    public function test_DevModeOffNoForceNoCacheAdapter_isRegistryCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->setCacheAdapter(null);

        $retVal = $config->isRegistryCacheEnabled();

        $this->assertFalse($retVal);
    }

    public function test_DevModeOffForceOnNoCacheAdapter_isRegistryCacheEnabled_ThrowsNoCacheAdapterException()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->forceRegistryCacheOn()
            ->setCacheAdapter(null);

        $this->expectException(NoCacheAdapterConfiguredException::class);

        $config->isRegistryCacheEnabled();
    }

    public function test_DevModeOffForceOnWithCacheAdapter_isRegistryCacheEnabled_ReturnsTrue()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->forceRegistryCacheOn()
            ->setCacheAdapter($this->mockedCacheAdapter);

        $retVal = $config->isRegistryCacheEnabled();

        $this->assertTrue($retVal);
    }

    public function test_DevModeOffForceOffNoCacheAdapter_isRegistryCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->forceRegistryCacheOff()
            ->setCacheAdapter(null);

        $retVal = $config->isRegistryCacheEnabled();

        $this->assertFalse($retVal);
    }

    public function test_DevModeOffForceOffWithCacheAdapter_isRegistryCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->forceRegistryCacheOff()
            ->setCacheAdapter($this->mockedCacheAdapter);

        $retVal = $config->isRegistryCacheEnabled();

        $this->assertFalse($retVal);
    }

    public function test_DevModeOnNoForceNoCacheAdapter_isRegistryCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->setCacheAdapter(null);

        $retVal = $config->isRegistryCacheEnabled();

        $this->assertFalse($retVal);
    }

    public function test_DevModeOnForceOnNoCacheAdapter_isRegistryCacheEnabled_ThrowsNoCacheAdapterException()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->forceRegistryCacheOn()
            ->setCacheAdapter(null);

        $this->expectException(NoCacheAdapterConfiguredException::class);

        $config->isRegistryCacheEnabled();
    }

    public function test_DevModeOnForceOnWithCacheAdapter_isRegistryCacheEnabled_ReturnsTrue()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->forceRegistryCacheOn()
            ->setCacheAdapter($this->mockedCacheAdapter);

        $retVal = $config->isRegistryCacheEnabled();

        $this->assertTrue($retVal);
    }

    public function test_DevModeOnForceOffNoCacheAdapter_isRegistryCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->forceRegistryCacheOff()
            ->setCacheAdapter(null);

        $retVal = $config->isRegistryCacheEnabled();

        $this->assertFalse($retVal);
    }

    public function test_DevModeOnForceOffWithCacheAdapter_isRegistryCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->forceRegistryCacheOff()
            ->setCacheAdapter($this->mockedCacheAdapter);

        $retVal = $config->isRegistryCacheEnabled();

        $this->assertFalse($retVal);
    }



    public function test_DevModeOffNoForceNoCacheAdapter_isControllersCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->setCacheAdapter(null);

        $retVal = $config->isControllersCacheEnabled();

        $this->assertFalse($retVal);
    }

    public function test_DevModeOffForceOnNoCacheAdapter_isControllersCacheEnabled_ThrowsNoCacheAdapterException()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->forceControllersCacheOn()
            ->setCacheAdapter(null);

        $this->expectException(NoCacheAdapterConfiguredException::class);

        $config->isControllersCacheEnabled();
    }

    public function test_DevModeOffForceOnWithCacheAdapter_isControllersCacheEnabled_ReturnsTrue()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->forceControllersCacheOn()
            ->setCacheAdapter($this->mockedCacheAdapter);

        $retVal = $config->isControllersCacheEnabled();

        $this->assertTrue($retVal);
    }

    public function test_DevModeOffForceOffNoCacheAdapter_isControllersCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->forceControllersCacheOff()
            ->setCacheAdapter(null);

        $retVal = $config->isControllersCacheEnabled();

        $this->assertFalse($retVal);
    }

    public function test_DevModeOffForceOffWithCacheAdapter_isControllersCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->forceControllersCacheOff()
            ->setCacheAdapter($this->mockedCacheAdapter);

        $retVal = $config->isControllersCacheEnabled();

        $this->assertFalse($retVal);
    }

    public function test_DevModeOnNoForceNoCacheAdapter_isControllersCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->setCacheAdapter(null);

        $retVal = $config->isControllersCacheEnabled();

        $this->assertFalse($retVal);
    }

    public function test_DevModeOnForceOnNoCacheAdapter_isControllersCacheEnabled_ThrowsNoCacheAdapterException()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->forceControllersCacheOn()
            ->setCacheAdapter(null);

        $this->expectException(NoCacheAdapterConfiguredException::class);

        $config->isControllersCacheEnabled();
    }

    public function test_DevModeOnForceOnWithCacheAdapter_isControllersCacheEnabled_ReturnsTrue()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->forceControllersCacheOn()
            ->setCacheAdapter($this->mockedCacheAdapter);

        $retVal = $config->isControllersCacheEnabled();

        $this->assertTrue($retVal);
    }

    public function test_DevModeOnForceOffNoCacheAdapter_isControllersCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->forceControllersCacheOff()
            ->setCacheAdapter(null);

        $retVal = $config->isControllersCacheEnabled();

        $this->assertFalse($retVal);
    }

    public function test_DevModeOnForceOffWithCacheAdapter_isControllersCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->forceControllersCacheOff()
            ->setCacheAdapter($this->mockedCacheAdapter);

        $retVal = $config->isControllersCacheEnabled();

        $this->assertFalse($retVal);
    }



    public function test_DevModeOffNoForce_isResolverCacheEnabled_ReturnsTrue()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode();

        $retVal = $config->isResolverCacheEnabled();

        $this->assertTrue($retVal);
    }

    public function test_DevModeOffForceOn_isResolverCacheEnabled_ReturnsTrue()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->forceResolverCacheOn();

        $retVal = $config->isResolverCacheEnabled();

        $this->assertTrue($retVal);
    }

    public function test_DevModeOffForceOff_isResolverCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->forceResolverCacheOff();

        $retVal = $config->isResolverCacheEnabled();

        $this->assertFalse($retVal);
    }

    public function test_DevModeOnNoForce_isResolverCacheEnabled_ReturnsTrue()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode();

        $retVal = $config->isResolverCacheEnabled();

        $this->assertTrue($retVal);
    }

    public function test_DevModeOnForceOn_isResolverCacheEnabled_ReturnsTrue()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->forceResolverCacheOn();

        $retVal = $config->isResolverCacheEnabled();

        $this->assertTrue($retVal);
    }

    public function test_DevModeOnForceOff_isResolverCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->forceResolverCacheOff();

        $retVal = $config->isResolverCacheEnabled();

        $this->assertFalse($retVal);
    }



    public function test_DevModeOffNoForce_isFetchAdapterCacheEnabled_ReturnsTrue()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode();

        $retVal = $config->isFetchAdapterCacheEnabled();

        $this->assertTrue($retVal);
    }

    public function test_DevModeOffForceOn_isFetchAdapterCacheEnabled_ReturnsTrue()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->forceFetchAdapterCacheOn();

        $retVal = $config->isFetchAdapterCacheEnabled();

        $this->assertTrue($retVal);
    }

    public function test_DevModeOffForceOff_isFetchAdapterCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->disableDevMode()
            ->forceFetchAdapterCacheOff();

        $retVal = $config->isFetchAdapterCacheEnabled();

        $this->assertFalse($retVal);
    }

    public function test_DevModeOnNoForce_isFetchAdapterCacheEnabled_ReturnsTrue()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode();

        $retVal = $config->isFetchAdapterCacheEnabled();

        $this->assertTrue($retVal);
    }

    public function test_DevModeOnForceOn_isFetchAdapterCacheEnabled_ReturnsTrue()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->forceFetchAdapterCacheOn();

        $retVal = $config->isFetchAdapterCacheEnabled();

        $this->assertTrue($retVal);
    }

    public function test_DevModeOnForceOff_isFetchAdapterCacheEnabled_ReturnsFalse()
    {
        $config = FrameworkConfiguration::create()
            ->enableDevMode()
            ->forceFetchAdapterCacheOff();

        $retVal = $config->isFetchAdapterCacheEnabled();

        $this->assertFalse($retVal);
    }
}