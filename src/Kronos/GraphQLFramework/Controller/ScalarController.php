<?php


namespace Kronos\GraphQLFramework\Controller;


use Kronos\GraphQLFramework\ContextAwareTrait;

abstract class ScalarController
{
	use ContextAwareTrait;

	public abstract function serializeScalarValue($value);
	public abstract function getScalarFromValue($value);
	public abstract function getScalarFromLiteral($value);
}