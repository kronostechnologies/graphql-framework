<?php


namespace Kronos\GraphQLFramework\Resolver;


use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Context\ContextUpdater;

class Resolver
{
	/**
	 * @var ContextUpdater
	 */
	protected $contextUpdater;

	/**
	 * @param FrameworkConfiguration $configuration
	 */
	public function __construct(FrameworkConfiguration $configuration)
	{
		$this->contextUpdater = new ContextUpdater();
		$this->contextUpdater->setConfiguration($configuration);
	}
}