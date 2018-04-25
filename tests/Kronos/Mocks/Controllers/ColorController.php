<?php


namespace Kronos\Mocks\Controllers;


use Kronos\GraphQLFramework\Controller\ScalarController;

class ColorController extends ScalarController
{
	public function serializeScalarValue($value)
	{
		return 'greenColor';
	}

	public function getScalarFromValue($value)
	{
		return 'redColor';
	}

	public function getScalarFromLiteral($value)
	{
		return 'blueColor';
	}
}