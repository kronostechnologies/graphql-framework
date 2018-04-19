<?php


namespace Kronos\Tests\GraphQLFramework\Resolver;


use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\NoMatchingControllerFoundException;
use Kronos\GraphQLFramework\Resolver\Exception\MissingFieldResolverException;
use Kronos\GraphQLFramework\Resolver\Resolver;
use Kronos\Tests\GraphQLFramework\Resolver\Controller\MockData;
use PHPUnit\Framework\TestCase;

class ResolverTest extends TestCase
{
	public function test_PreconfiguredExistingTypeAndField_resolveFieldOfType_ReturnsResolverResult()
	{
		$configuration = new FrameworkConfiguration();
		$configuration->setControllersDirectory(MockData::CONTROLLERS_TEST_DIR);
		$resolver = new Resolver($configuration);

		$retVal = $resolver->resolveFieldOfType(null, null, 'A', 'testField');

		$this->assertSame('Hello', $retVal);
	}

	public function test_PreconfiguredNonExistingType_resolveFieldOfType_ThrowsNoMatchingControllerFoundException()
	{
		$configuration = new FrameworkConfiguration();
		$configuration->setControllersDirectory(MockData::CONTROLLERS_TEST_DIR);
		$resolver = new Resolver($configuration);

		$this->expectException(NoMatchingControllerFoundException::class);

		$resolver->resolveFieldOfType(null, null, 'AAA', 'testField');
	}

	public function test_PreconfiguredNonExistingField_resolveFieldOfType_ThrowsNoMatchingControllerFoundException()
	{
		$configuration = new FrameworkConfiguration();
		$configuration->setControllersDirectory(MockData::CONTROLLERS_TEST_DIR);
		$resolver = new Resolver($configuration);

		$this->expectException(MissingFieldResolverException::class);

		$resolver->resolveFieldOfType(null, null, 'A', 'nonExistingField');
	}
}