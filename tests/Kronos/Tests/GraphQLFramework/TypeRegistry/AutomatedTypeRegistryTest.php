<?php


namespace Kronos\Tests\GraphQLFramework\TypeRegistry;


use PHPUnit\Framework\TestCase;

class AutomatedTypeRegistryTest extends TestCase
{
	public function test_WithQueryTypeSchema_getQueryType_ReturnsTypeObject()
	{

	}

	public function test_WithQueryTypeSchema_getQueryType_DefinitionHasQueryType()
	{

	}

	public function test_NoQueryTypeSchema_getQueryType_ThrowsQueryMustBeProvidedException()
	{

	}

	public function test_WithMutationTypeSchema_getMutationType_ReturnsTypeObject()
	{

	}

	public function test_WithMutationTypeSchema_getMutationType_DefinitionHasMutationType()
	{

	}

	public function test_NoMutationTypeSchema_getMutationType_ReturnsNull()
	{

	}

	public function test_WithMutationTypeSchema_doesTypeExist_ReturnsTrue()
	{

	}

	public function test_NoMutationTypeSchema_doesTypeExist_ReturnsFalse()
	{

	}

	public function test_RecursiveTypeSchemaOnExistingType_doesTypeExist_ReturnsTrue()
	{

	}

	public function test_RecursiveTypeSchemaOnNonExistingType_doesTypeExist_ReturnsFalse()
	{

	}

	public function test_WithMutationTypeSchema_getTypeByName_ReturnsMutationType()
	{

	}

	public function test_NoMutationTypeSchema_getTypeByName_ThrowsTypeNotFoundException()
	{

	}


	public function test_WithQueryTypeSchema_getQueryTypeAndGetTypeByName_ReturnsSameType()
	{

	}

	public function test_WithMutationTypeSchema_getMutationTypeAndGetTypeByName_ReturnsSameType()
	{

	}

}