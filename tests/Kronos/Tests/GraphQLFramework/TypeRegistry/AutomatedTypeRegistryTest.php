<?php


namespace Kronos\Tests\GraphQLFramework\TypeRegistry;


use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Resolver;
use Kronos\GraphQLFramework\TypeRegistry\AutomatedTypeRegistry;
use Kronos\GraphQLFramework\TypeRegistry\Exception\TypeNotFoundException;
use PHPUnit\Framework\TestCase;

class AutomatedTypeRegistryTest extends TestCase
{
	const DIR_MUTABLE_SCHEMA = __DIR__ . '/../../../Mocks/Schema/Mutable/Types/';
	const DIR_IMMUTABLE_SCHEMA = __DIR__ . '/../../../Mocks/Schema/Immutable/Types/';
	const DIR_INVALID_SCHEMA = __DIR__ . '/../../../Mocks/Schema/Invalid/Types/';

    /**
     * @var Resolver
     */
	protected $resolver;

	protected function setUp()
    {
        $this->resolver = new Resolver(new FrameworkConfiguration());
    }

	public function test_WithQueryTypeSchema_getQueryType_ReturnsTypeObject()
	{
		$registry = new AutomatedTypeRegistry($this->resolver, self::DIR_MUTABLE_SCHEMA);

		$retVal = $registry->getTypeByName('Query');

		$this->assertInstanceOf(ObjectType::class, $retVal);
	}

	public function test_WithQueryTypeSchema_getQueryType_DefinitionHasQueryType()
	{
		$registry = new AutomatedTypeRegistry($this->resolver, self::DIR_MUTABLE_SCHEMA);

		$retVal = $registry->getTypeByName('Query');

		$this->assertSame('Query', $retVal->name);
	}

	public function test_NoQueryTypeSchema_getQueryType_ThrowsTypeNotFoundException()
	{
		$registry = new AutomatedTypeRegistry($this->resolver, self::DIR_INVALID_SCHEMA);

		$this->expectException(TypeNotFoundException::class);

		$registry->getQueryType();
	}

	public function test_WithMutationTypeSchema_getMutationType_ReturnsTypeObject()
	{
		$registry = new AutomatedTypeRegistry($this->resolver, self::DIR_MUTABLE_SCHEMA);

		$retVal = $registry->getMutationType();

		$this->assertInstanceOf(Type::class, $retVal);
	}

	public function test_WithMutationTypeSchema_getMutationType_DefinitionHasMutationType()
	{
		$registry = new AutomatedTypeRegistry($this->resolver, self::DIR_MUTABLE_SCHEMA);

		$retVal = $registry->getTypeByName('Mutation');

		$this->assertSame('Mutation', $retVal->name);
	}

	public function test_NoMutationTypeSchema_getMutationType_ReturnsNull()
	{
		$registry = new AutomatedTypeRegistry($this->resolver, self::DIR_IMMUTABLE_SCHEMA);

		$retVal = $registry->getMutationType();

		$this->assertSame(null, $retVal);
	}

	public function test_WithMutationTypeSchema_doesTypeExist_ReturnsTrue()
	{
		$registry = new AutomatedTypeRegistry($this->resolver, self::DIR_MUTABLE_SCHEMA);

		$retVal = $registry->doesTypeExist('Mutation');

		$this->assertTrue($retVal);
	}

	public function test_NoMutationTypeSchema_doesTypeExist_ReturnsFalse()
	{
		$registry = new AutomatedTypeRegistry($this->resolver, self::DIR_IMMUTABLE_SCHEMA);

		$retVal = $registry->doesTypeExist('Mutation');

		$this->assertFalse($retVal);
	}

	public function test_NoQueryTypeSchema_getTypeByName_ThrowsTypeNotFoundException()
	{
		$registry = new AutomatedTypeRegistry($this->resolver, self::DIR_INVALID_SCHEMA);

		$this->expectException(TypeNotFoundException::class);

		$registry->getTypeByName('Query');
	}

	public function test_WithQueryTypeSchema_multipleGetQueryType_ReturnsSameTypeInstance()
	{
		$registry = new AutomatedTypeRegistry($this->resolver, self::DIR_MUTABLE_SCHEMA);

		$fetch1 = $registry->getQueryType();
		$fetch2 = $registry->getTypeByName('Query');

		$this->assertSame($fetch1, $fetch2);
	}

	public function test_WithQueryTypeSchema_getQueryTypeAndGetTypeByName_ReturnsSameType()
	{
		$registry = new AutomatedTypeRegistry($this->resolver, self::DIR_MUTABLE_SCHEMA);

		$queryTypeVal = $registry->getQueryType();
		$byNameVal = $registry->getTypeByName('Query');

		$this->assertSame($queryTypeVal, $byNameVal);
	}

	public function test_WithMutationTypeSchema_getMutationTypeAndGetTypeByName_ReturnsSameType()
	{
		$registry = new AutomatedTypeRegistry($this->resolver, self::DIR_MUTABLE_SCHEMA);

		$mutationTypeVal = $registry->getMutationType();
		$byNameVal = $registry->getTypeByName('Mutation');

		$this->assertSame($mutationTypeVal, $byNameVal);
	}

}
