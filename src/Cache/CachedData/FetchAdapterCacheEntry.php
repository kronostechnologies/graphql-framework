<?php

/**
 * An entity representing a FetchAdapter cache entry.
 */
class FetchAdapterCacheEntry
{
	/**
	 * @var string
	 */
	protected $fetchAdapterFQN;

	/**
	 * @var array
	 */
	protected $filters;

	/**
	 * @var array
	 */
	protected $results;

	public function __construct($fetchAdapterFQN, array $filters)
	{
		$this->fetchAdapterFQN = $fetchAdapterFQN;
		$this->filters = $filters;
	}

	/**
	 * @return string
	 */
	public function getFetchAdapterFQN()
	{
		return $this->fetchAdapterFQN;
	}

	/**
	 * @return array
	 */
	public function getFilters()
	{
		return $this->filters;
	}

	/**
	 * @return array
	 */
	public function getResults()
	{

	}
}