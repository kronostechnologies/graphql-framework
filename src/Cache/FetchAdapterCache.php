<?php


/**
 * A per-query cache which can recover a FetchAdapter result. This cache is discarded once the
 * query is over.
 */
class FetchAdapterCache
{
	/**
	 * @var FetchAdapterCacheEntry
	 */
	protected $cacheEntries = [];

	/**
	 * Registers a GraphQL FetchAdapter result in the cache.
	 *
	 * @param string $fetchAdapterFQN
	 * @param array $filters
	 * @param array $results
	 */
	public function registerFetchResult($fetchAdapterFQN, array $filters, $results)
	{

	}

	/**
	 * Returns the cache result if found, or null if not (optimized for fetch and return at the same time).
	 *
	 * @param string $fetchAdapterFQN
	 * @param array $filters
	 */
	public function getCachedResult($fetchAdapterFQN, array $filters)
	{

	}
}