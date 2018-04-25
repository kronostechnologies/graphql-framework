<?php


namespace Kronos\Mocks\Controllers;


use Kronos\GraphQLFramework\Controller\InterfaceController;

class AnimalController extends InterfaceController
{

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function resolveInterfaceType($value)
	{
		return 'Cat';
	}
}