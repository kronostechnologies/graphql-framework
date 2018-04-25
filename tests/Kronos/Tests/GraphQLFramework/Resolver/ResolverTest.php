<?php


namespace Kronos\Tests\GraphQLFramework\Resolver;


use GraphQLConfiguration;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Controller\ControllerFinder;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\InvalidControllerTypeException;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\NoMatchingControllerFoundException;
use Kronos\GraphQLFramework\Resolver\Exception\MissingFieldResolverException;
use Kronos\GraphQLFramework\Resolver\Resolver;
use Kronos\GraphQLFramework\Resolver\ResolverFactory;
use Kronos\Tests\GraphQLFramework\Resolver\Controller\MockData;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ResolverTest extends TestCase
{
	/**
	 * @var FrameworkConfiguration
	 */
	protected $configuration;

	/**
	 * @var Resolver
	 */
	protected $resolver;

	public function setUp()
	{
		$this->configuration = new FrameworkConfiguration();
		$this->configuration->setControllersDirectory(MockData::CONTROLLERS_TEST_DIR);

		$this->resolver = new Resolver($this->configuration);
	}

	public function test_PreconfiguredExistingTypeAndField_resolveFieldOfType_ReturnsResolverResult()
	{
		$resolver = $this->resolver;

		$retVal = $resolver->resolveFieldOfType(null, null, 'A', 'testField');

		$this->assertSame('Hello', $retVal);
	}

	public function test_PreconfiguredNonExistingType_resolveFieldOfType_ThrowsNoMatchingControllerFoundException()
	{
		$resolver = $this->resolver;

		$this->expectException(NoMatchingControllerFoundException::class);

		$resolver->resolveFieldOfType(null, null, 'AAA', 'testField');
	}

	public function test_PreconfiguredNonExistingField_resolveFieldOfType_ThrowsNoMatchingControllerFoundException()
	{
		$resolver = $this->resolver;

		$this->expectException(MissingFieldResolverException::class);

		$resolver->resolveFieldOfType(null, null, 'A', 'nonExistingField');
	}

	public function test_MistypedController_resolveFieldOfType_ThrowsInvalidControllerTypeException()
	{
		$resolver = $this->resolver;

		$this->expectException(InvalidControllerTypeException::class);

		$resolver->resolveFieldOfType(null, null, 'Color', 'nonExistingField');
	}

	public function test_PreconfiguredExistingScalarController_getScalarFromValue_ReturnsResult()
	{
		$resolver = $this->resolver;

		$retVal = $resolver->getScalarFromValue('Color', 'red');

		$this->assertSame('redColor', $retVal);
	}

	public function test_PreconfiguredNonExistingType_getScalarFromValue_ThrowsNoMatchingControllerFoundException()
	{
		$resolver = $this->resolver;

		$this->expectException(NoMatchingControllerFoundException::class);

		$resolver->getScalarFromValue('nonexistingscalartype', '111');
	}

	public function test_PreconfiguredExistingScalarController_getScalarFromLiteral_ReturnsResult()
	{
		$resolver = $this->resolver;

		$retVal = $resolver->getScalarFromLiteral('Color', 'blue');

		$this->assertSame('blueColor', $retVal);
	}

	public function test_PreconfiguredNonExistingType_getScalarFromLiteral_ThrowsNoMatchingControllerFoundException()
	{
		$resolver = $this->resolver;

		$this->expectException(NoMatchingControllerFoundException::class);

		$resolver->getScalarFromLiteral('nonexistingscalartype', '111');
	}

	public function test_PreconfiguredExistingScalarController_serializeScalarValue_ReturnsResult()
	{
		$resolver = $this->resolver;

		$retVal = $resolver->serializeScalarValue('Color', 'green');

		$this->assertSame('greenColor', $retVal);
	}

	public function test_PreconfiguredNonExistingType_serializeScalarValue_ThrowsNoMatchingControllerFoundException()
	{
		$resolver = $this->resolver;

		$this->expectException(NoMatchingControllerFoundException::class);

		$resolver->serializeScalarValue('nonexistingscalartype', '111');
	}

	public function test_MistypedBaseController_getScalarFromValue_ThrowsInvalidControllerTypeException()
	{
		$resolver = $this->resolver;

		$this->expectException(InvalidControllerTypeException::class);

		$resolver->getScalarFromValue('A', '111');
	}

	public function test_MistypedBaseController_getScalarFromLiteral_ThrowsInvalidControllerTypeException()
	{
		$resolver = $this->resolver;

		$this->expectException(InvalidControllerTypeException::class);

		$resolver->getScalarFromLiteral('A', '111');
	}

	public function test_MistypedBaseController_serializeScalarValue_ThrowsInvalidControllerTypeException()
	{
		$resolver = $this->resolver;

		$this->expectException(InvalidControllerTypeException::class);

		$resolver->serializeScalarValue('A', '111');
	}

	public function test_PreconfiguredExistingInterfaceController_resolveInterfaceType_ReturnsResult()
	{
		$resolver = $this->resolver;

		$retVal = $resolver->resolveInterfaceType('Animal', 'anystring');

		$this->assertSame('Cat', $retVal);
	}

	public function test_PreconfiguredNonExistingInterfaceController_resolveInterfaceType_ThrowsNoMatchingControllerFoundException()
	{
		$resolver = $this->resolver;

		$this->expectException(NoMatchingControllerFoundException::class);

		$resolver->resolveInterfaceType('nonexistinginterfacetype', '111');
	}

	public function test_MistypedBaseController_resolveInterfaceType_ThrowsInvalidControllerTypeException()
	{
		$resolver = $this->resolver;

		$this->expectException(InvalidControllerTypeException::class);

		$resolver->resolveInterfaceType('Color', '111');
	}
}