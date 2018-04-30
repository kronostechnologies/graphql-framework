<?php


namespace Kronos\Tests\GraphQLFramework\Hydrator;


use Kronos\GraphQLFramework\Hydrator\DTOHydrator;
use Kronos\GraphQLFramework\Hydrator\Exception\DTORequiresArgumentsException;
use Kronos\GraphQLFramework\Hydrator\UndefinedValue;
use Kronos\Mocks\DTO\BasicDTO;
use Kronos\Mocks\DTO\DepthDTO;
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
}
