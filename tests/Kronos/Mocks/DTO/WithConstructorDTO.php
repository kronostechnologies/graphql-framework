<?php


namespace Kronos\Mocks\DTO;


class WithConstructorDTO
{
	protected $value;

	public function __construct(array $value)
	{
		$this->value = $value;
	}
}
