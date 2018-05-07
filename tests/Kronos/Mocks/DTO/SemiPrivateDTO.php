<?php


namespace Kronos\Mocks\DTO;


class SemiPrivateDTO
{
	public $fieldOne;

	protected $fieldTwo;

	private $fieldThree;

	public $fieldFour;

	/**
	 * @return mixed
	 */
	public function getFieldTwo()
	{
		return $this->fieldTwo;
	}

	/**
	 * @return mixed
	 */
	public function getFieldThree()
	{
		return $this->fieldThree;
	}
}
