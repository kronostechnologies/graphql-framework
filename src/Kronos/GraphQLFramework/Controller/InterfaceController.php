<?php


namespace Kronos\GraphQLFramework\Controller;


use Kronos\GraphQLFramework\ContextAwareTrait;

abstract class InterfaceController
{
	use ContextAwareTrait;

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public abstract function resolveInterfaceType($value);
}