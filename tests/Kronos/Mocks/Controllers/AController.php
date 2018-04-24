<?php


namespace Kronos\Mocks\Controllers;


use Kronos\GraphQLFramework\Controller\BaseController;

class AController extends BaseController
{
	public function getTestField()
	{
		return 'Hello';
	}
}