<?php


namespace Kronos\Mocks\Controllers;


use Kronos\GraphQLFramework\BaseController;

class AController extends BaseController
{
	public function getTestField()
	{
		return 'Hello';
	}
}