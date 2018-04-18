<?php


namespace Kronos\Tests\GraphQLFramework\Resolver\Context;


use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Context\ContextUpdater;
use Kronos\GraphQLFramework\Resolver\Context\Exception\ArgumentsMustBeArrayException;
use Kronos\GraphQLFramework\Resolver\Context\Exception\VariablesMustBeArrayException;
use Kronos\GraphQLFramework\Resolver\Context\GraphQLContext;
use PHPUnit\Framework\TestCase;

class ContextUpdaterTest extends TestCase
{
	public function test_New_getCurrentContext_ReturnsContextInstance()
	{
		$updater = new ContextUpdater();

		$context = $updater->getActiveContext();

		$this->assertInstanceOf(GraphQLContext::class, $context);
	}

	public function test_New_getCurrentContext_DefaultsAreSet()
	{
		$updater = new ContextUpdater();

		$context = $updater->getActiveContext();

		$this->assertInstanceOf(FrameworkConfiguration::class, $context->getConfiguration());
		$this->assertSame([], $context->getCurrentArguments());
		$this->assertSame(null, $context->getCurrentParentObject());
		$this->assertSame("", $context->getFullQueryString());
		$this->assertSame([], $context->getVariables());
	}

	public function test_NoRootNoArgs_setCurrentResolverPath_CurrentParentObjectIsNull()
	{
		$updater = new ContextUpdater();
		$updater->setCurrentResolverPath(null, null);

		$context = $updater->getActiveContext();

		$this->assertSame(null, $context->getCurrentParentObject());
	}

	public function test_RootNoArgs_setCurrentResolverPath_CurrentParentObjectIsRoot()
	{
		$root = new \stdClass();
		$updater = new ContextUpdater();
		$updater->setCurrentResolverPath($root, null);

		$context = $updater->getActiveContext();

		$this->assertSame($root, $context->getCurrentParentObject());
	}

	public function test_NoRootNoArgs_setCurrentResolverPath_CurrentArgumentsIsEmptyArray()
	{
		$updater = new ContextUpdater();
		$updater->setCurrentResolverPath(null, null);

		$context = $updater->getActiveContext();

		$this->assertSame([], $context->getCurrentArguments());
	}

	public function test_InvalidArgsType_setCurrentResolverPath_ThrowsArgumentsMustBeArray()
	{
		$updater = new ContextUpdater();

		$this->expectException(ArgumentsMustBeArrayException::class);

		$updater->setCurrentResolverPath(null, new \stdClass());
	}

	public function test_NullVariables_setInitialData_VariablesIsEmptyArray()
	{
		$updater = new ContextUpdater();
		$updater->setInitialData(null, null);

		$context = $updater->getActiveContext();

		$this->assertSame([], $context->getVariables());
	}

	public function test_SetVariables_setInitialData_VariablesIsRightValue()
	{
		$updater = new ContextUpdater();
		$updater->setInitialData(null, ['a' => '1', 'b' => '2']);

		$context = $updater->getActiveContext();

		$this->assertSame(['a' => '1', 'b' => '2'], $context->getVariables());
	}

	public function test_SetStdClassVariables_setInitialData_ThrowsVariablesMustBeArrayException()
	{
		$updater = new ContextUpdater();

		$this->expectException(VariablesMustBeArrayException::class);

		$updater->setInitialData(null, new \stdClass());
	}
}