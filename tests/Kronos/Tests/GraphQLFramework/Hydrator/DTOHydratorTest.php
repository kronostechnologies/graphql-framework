<?php


namespace Kronos\Tests\GraphQLFramework\Hydrator;


use Kronos\GraphQLFramework\Hydrator\DTOHydrator;
use Kronos\GraphQLFramework\Hydrator\Exception\DTORequiresArgumentsException;
use Kronos\GraphQLFramework\Hydrator\Exception\FQNDefinitionMissingException;
use Kronos\GraphQLFramework\Hydrator\Exception\InvalidDefinitionClassException;
use Kronos\GraphQLFramework\Hydrator\Exception\InvalidFieldValueException;
use Kronos\GraphQLFramework\Hydrator\UndefinedValue;
use Kronos\Mocks\DTO\BasicDTO;
use Kronos\Mocks\DTO\Definition\BasicDTODefinition;
use Kronos\Mocks\DTO\Definition\DepthDTODefinition;
use Kronos\Mocks\DTO\Definition\MissingFQNBasicDTODefinition;
use Kronos\Mocks\DTO\Definition\MultiDepthLevelDTODefinition;
use Kronos\Mocks\DTO\DepthDTO;
use Kronos\Mocks\DTO\DepthSubDTO;
use Kronos\Mocks\DTO\SemiPrivateDTO;
use Kronos\Mocks\DTO\WithConstructorDTO;
use PHPUnit\Framework\TestCase;

class DTOHydratorTest extends TestCase
{
	/**
	 * @var DTOHydrator
	 */
	protected $hydrator;

	public function setUp()
	{
		$this->hydrator = new DTOHydrator();
	}

	protected function getFilledBasicDTOArray()
	{
		return [
			'fieldOne' => 1,
			'fieldTwo' => 2,
			'fieldThree' => 3
		];
	}

	protected function getPartialBasicDTOArray()
	{
		return [
			'fieldOne' => 1,
			'fieldThree' => 3
		];
	}

	protected function getFilledDepthDTOArray()
	{
		return [
			'fieldOne' => 1,
			'fieldTwo' => 2,
			'subField' => [
				'deepFieldOne' => 'deep1',
				'deepFieldTwo' => 'deep2'
			]
		];
	}

	protected function getFilledMultiDepthDTOArray()
	{
		return [
			'fieldOne' => 1,
			'fieldTwo' => 2,
			'subField' => [
				'fieldOne' => 'aaa',
				'fieldTwo' => true,
				'subField' => [
					'deepFieldOne' => 'verydeep1',
					'deepFieldTwo' => 'verydeep2'
				]
			]
		];
	}

	protected function getFirstLevelSubFieldUnsetMultiDepthDTOArray()
	{
		return [
			'fieldOne' => 1,
			'fieldTwo' => 2,
			'subField' => [
				'fieldOne' => 'aaa',
				'fieldTwo' => true,
			]
		];
	}

	protected function getScalarSubFieldDepthDTOArray()
	{
		return [
			'subField' => 'iamscalar'
		];
	}

	protected function getFilledSemiPrivateDTO()
	{
		return [
			'fieldOne' => 1,
			'fieldTwo' => 2, // Should not be set (protected)
			'fieldThree' => 3, // Should not be set (private)
			'fieldFour' => 4,
		];
	}

	public function test_BasicDTOAllDataSet_fromSimpleArray_ReturnsBasicDTOInstance()
	{
		$arrSet = $this->getFilledBasicDTOArray();

		/** @var BasicDTO $retVal */
		$retVal = $this->hydrator->fromSimpleArray(BasicDTO::class, $arrSet);

		$this->assertInstanceOf(BasicDTO::class, $retVal);
	}

	public function test_BasicDTOAllDataSet_fromSimpleArray_NoFieldOnDTOIsUndefinedValue()
	{
		$arrSet = $this->getFilledBasicDTOArray();

		/** @var BasicDTO $retVal */
		$retVal = $this->hydrator->fromSimpleArray(BasicDTO::class, $arrSet);

		$this->assertNotInstanceOf(UndefinedValue::class, $retVal->fieldOne);
		$this->assertNotInstanceOf(UndefinedValue::class, $retVal->fieldTwo);
		$this->assertNotInstanceOf(UndefinedValue::class, $retVal->fieldThree);
	}

	public function test_BasicDTOAllDataSet_fromSimpleArray_DTOHasRightValues()
	{
		$arrSet = $this->getFilledBasicDTOArray();

		/** @var BasicDTO $retVal */
		$retVal = $this->hydrator->fromSimpleArray(BasicDTO::class, $arrSet);

		$this->assertSame(1, $retVal->fieldOne);
		$this->assertSame(2, $retVal->fieldTwo);
		$this->assertSame(3, $retVal->fieldThree);
	}

	public function test_BasicDTONoDataSet_fromSimpleArray_AllDTOFieldAreUndefinedValue()
	{
		$arrSet = [];

		/** @var BasicDTO $retVal */
		$retVal = $this->hydrator->fromSimpleArray(BasicDTO::class, $arrSet);

		$this->assertInstanceOf(UndefinedValue::class, $retVal->fieldOne);
		$this->assertInstanceOf(UndefinedValue::class, $retVal->fieldTwo);
		$this->assertInstanceOf(UndefinedValue::class, $retVal->fieldThree);
	}

	public function test_BasicDTOPartialDataSet_fromSimpleArray_DTOFieldsAreSetToValuesCorrectly()
	{
		$arrSet = $this->getPartialBasicDTOArray();

		/** @var BasicDTO $retVal */
		$retVal = $this->hydrator->fromSimpleArray(BasicDTO::class, $arrSet);

		$this->assertSame(1, $retVal->fieldOne);
		$this->assertInstanceOf(UndefinedValue::class, $retVal->fieldTwo);
		$this->assertSame(3, $retVal->fieldThree);
	}

	public function test_DepthDTOFilledDataSet_fromSimpleArray_ScalarValuesAreSetCorrectly()
	{
		$arrSet = $this->getFilledDepthDTOArray();

		/** @var DepthDTO $retVal */
		$retVal = $this->hydrator->fromSimpleArray(DepthDTO::class, $arrSet);

		$this->assertSame(1, $retVal->fieldOne);
		$this->assertSame(2, $retVal->fieldTwo);
	}

	public function test_DepthDTOFilledDataSet_fromSimpleArray_DepthValuesIsArray()
	{
		$arrSet = $this->getFilledDepthDTOArray();

		/** @var DepthDTO $retVal */
		$retVal = $this->hydrator->fromSimpleArray(DepthDTO::class, $arrSet);

		$this->assertSame([
			'deepFieldOne' => 'deep1',
			'deepFieldTwo' => 'deep2'
		], $retVal->subField);
	}

	public function test_SemiPrivateDTOFilledDataSet_fromSimpleArray_PublicValuesAreSet()
	{
		$arrSet = $this->getFilledSemiPrivateDTO();

		/** @var SemiPrivateDTO $retVal */
		$retVal = $this->hydrator->fromSimpleArray(SemiPrivateDTO::class, $arrSet);

		$this->assertSame(1, $retVal->fieldOne);
		$this->assertSame(4, $retVal->fieldFour);
	}

	public function test_SemiPrivateDTOFilledDataSet_fromSimpleArray_ProtectedValueIsUnchanged()
	{
		$arrSet = $this->getFilledSemiPrivateDTO();

		/** @var SemiPrivateDTO $retVal */
		$retVal = $this->hydrator->fromSimpleArray(SemiPrivateDTO::class, $arrSet);

		$this->assertSame(null, $retVal->getFieldTwo());
	}

	public function test_SemiPrivateDTOFilledDataSet_fromSimpleArray_PrivateValueIsUnchanged()
	{
		$arrSet = $this->getFilledSemiPrivateDTO();

		/** @var SemiPrivateDTO $retVal */
		$retVal = $this->hydrator->fromSimpleArray(SemiPrivateDTO::class, $arrSet);

		$this->assertSame(null, $retVal->getFieldThree());
	}

	public function test_WithConstructorDTO_fromSimpleArray_ThrowsException()
	{
		$arrSet = $this->getFilledSemiPrivateDTO();

		$this->expectException(DTORequiresArgumentsException::class);

		$this->hydrator->fromSimpleArray(WithConstructorDTO::class, $arrSet);
	}

	public function test_BasicDTOAllDataSet_fromDTODefinitionWithFQN_ReturnsBasicDTOInstance()
	{
		$arrSet = $this->getFilledBasicDTOArray();

		/** @var BasicDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(BasicDTODefinition::class, $arrSet);

		$this->assertInstanceOf(BasicDTO::class, $retVal);
	}

	public function test_BasicDTOAllDataSet_fromDTODefinitionWithInstance_ReturnsBasicDTOInstance()
	{
		$arrSet = $this->getFilledBasicDTOArray();
		$dtoDefinition = new BasicDTODefinition();

		/** @var BasicDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition($dtoDefinition, $arrSet);

		$this->assertInstanceOf(BasicDTO::class, $retVal);
	}

	public function test_BasicDTOAllDataSet_fromDTODefinitionWithArray_ReturnsBasicDTOInstance()
	{
		$arrSet = $this->getFilledBasicDTOArray();
		$dtoDefinition = new BasicDTODefinition();

		/** @var BasicDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition($dtoDefinition->getDtoDefinition(), $arrSet);

		$this->assertInstanceOf(BasicDTO::class, $retVal);
	}

	public function test_BasicDTOAllDataSet_fromDTODefinition_NoFieldOnDTOIsUndefinedValue()
	{
		$arrSet = $this->getFilledBasicDTOArray();

		/** @var BasicDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(BasicDTODefinition::class, $arrSet);

		$this->assertNotInstanceOf(UndefinedValue::class, $retVal->fieldOne);
		$this->assertNotInstanceOf(UndefinedValue::class, $retVal->fieldTwo);
		$this->assertNotInstanceOf(UndefinedValue::class, $retVal->fieldThree);
	}

	public function test_BasicDTOAllDataSet_fromDTODefinition_DTOHasRightValues()
	{
		$arrSet = $this->getFilledBasicDTOArray();

		/** @var BasicDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(BasicDTODefinition::class, $arrSet);

		$this->assertSame(1, $retVal->fieldOne);
		$this->assertSame(2, $retVal->fieldTwo);
		$this->assertSame(3, $retVal->fieldThree);
	}

	public function test_BasicDTOAllDataSet_fromDTODefinition_AllDTOFieldAreUndefinedValue()
	{
		$arrSet = [];

		/** @var BasicDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(BasicDTODefinition::class, $arrSet);

		$this->assertInstanceOf(UndefinedValue::class, $retVal->fieldOne);
		$this->assertInstanceOf(UndefinedValue::class, $retVal->fieldTwo);
		$this->assertInstanceOf(UndefinedValue::class, $retVal->fieldThree);
	}

	public function test_BasicDTOPartialDataSet_fromDTODefinition_DTOFieldsAreSetToValuesCorrectly()
	{
		$arrSet = $this->getPartialBasicDTOArray();

		/** @var BasicDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(BasicDTODefinition::class, $arrSet);

		$this->assertSame(1, $retVal->fieldOne);
		$this->assertInstanceOf(UndefinedValue::class, $retVal->fieldTwo);
		$this->assertSame(3, $retVal->fieldThree);
	}

	public function test_DepthDTOAllDataSet_fromDTODefinition_ScalarValuesAreSetCorrectly()
	{
		$arrSet = $this->getFilledDepthDTOArray();

		/** @var DepthDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(DepthDTODefinition::class, $arrSet);

		$this->assertSame(1, $retVal->fieldOne);
		$this->assertSame(2, $retVal->fieldTwo);
	}

	public function test_DepthDTOAllDataSet_fromDTODefinition_RootSubFieldHasRightType()
	{
		$arrSet = $this->getFilledDepthDTOArray();

		/** @var DepthDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(DepthDTODefinition::class, $arrSet);

		$this->assertInstanceOf(DepthSubDTO::class, $retVal->subField);
	}

	public function test_DepthDTOAllDataSet_fromDTODefinition_SubFieldScalarValuesAreSetCorrectly()
	{
		$arrSet = $this->getFilledDepthDTOArray();

		/** @var DepthDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(DepthDTODefinition::class, $arrSet);

		$this->assertSame('deep1', $retVal->subField->deepFieldOne);
		$this->assertSame('deep2', $retVal->subField->deepFieldTwo);
	}

	public function test_DepthDTOScalarSubField_fromDTODefinition_ThrowsInvalidFieldValueException()
	{
		$arrSet = $this->getScalarSubFieldDepthDTOArray();

		$this->expectException(InvalidFieldValueException::class);

		$this->hydrator->fromDTODefinition(DepthDTODefinition::class, $arrSet);
	}

	public function test_MultiDepthDTOAllDataSet_fromDTODefinition_RootScalarValuesAreSetCorrectly()
	{
		$arrSet = $this->getFilledMultiDepthDTOArray();

		/** @var DepthDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(MultiDepthLevelDTODefinition::class, $arrSet);

		$this->assertSame(1, $retVal->fieldOne);
		$this->assertSame(2, $retVal->fieldTwo);
	}

	public function test_MultiDepthDTOAllDataSet_fromDTODefinition_RootSubFieldHasRightType()
	{
		$arrSet = $this->getFilledMultiDepthDTOArray();

		/** @var DepthDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(MultiDepthLevelDTODefinition::class, $arrSet);

		$this->assertInstanceOf(DepthDTO::class, $retVal->subField);
	}

	public function test_MultiDepthDTOAllDataSet_fromDTODefinition_FirstLevelScalarValuesAreSetCorrectly()
	{
		$arrSet = $this->getFilledMultiDepthDTOArray();

		/** @var DepthDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(MultiDepthLevelDTODefinition::class, $arrSet);

		$this->assertSame('aaa', $retVal->subField->fieldOne);
		$this->assertSame(true, $retVal->subField->fieldTwo);
	}

	public function test_MultiDepthDTOAllDataSet_fromDTODefinition_FirstLevelRootSubFieldHasRightType()
	{
		$arrSet = $this->getFilledMultiDepthDTOArray();

		/** @var DepthDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(MultiDepthLevelDTODefinition::class, $arrSet);

		$this->assertInstanceOf(DepthSubDTO::class, $retVal->subField->subField);
	}

	public function test_MultiDepthDTOAllDataSet_fromDTODefinition_LastLevelScalarValuesAreSetCorrectly()
	{
		$arrSet = $this->getFilledMultiDepthDTOArray();

		/** @var DepthDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(MultiDepthLevelDTODefinition::class, $arrSet);

		$this->assertSame('verydeep1', $retVal->subField->subField->deepFieldOne);
		$this->assertSame('verydeep2', $retVal->subField->subField->deepFieldTwo);
	}

	public function test_MultiDepthDTOFirstLevelSubFieldUnset_fromDTODefinition_FirstLevelSubFieldIsNull()
	{
		$arrSet = $this->getFirstLevelSubFieldUnsetMultiDepthDTOArray();

		/** @var DepthDTO $retVal */
		$retVal = $this->hydrator->fromDTODefinition(MultiDepthLevelDTODefinition::class, $arrSet);

		$this->assertInstanceOf(UndefinedValue::class, $retVal->subField->subField);
	}

	public function test_InvalidDefinition_fromDTODefinition_ThrowsInvalidDefinitionClassException()
	{
		$arrSet = [];

		$this->expectException(InvalidDefinitionClassException::class);

		$this->hydrator->fromDTODefinition(new \stdClass(), $arrSet);
	}

	public function test_MissingFQNDefinition_fromDTODefinition_ThrowsFQNDefinitionMissingException()
	{
		$arrSet = [];

		$this->expectException(FQNDefinitionMissingException::class);

		$this->hydrator->fromDTODefinition(MissingFQNBasicDTODefinition::class, $arrSet);
	}
}
