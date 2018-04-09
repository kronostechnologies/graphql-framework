<?php

/**
 * Trait used to make the FetchAdapters aware of the FetchAdapterCache object.
 */
trait FetchAdapterCacheAwareTrait
{
	/**
	 * @var FetchAdapterCache
	 */
	protected $fetchAdapterCache;

	/**
	 * @param FetchAdapterCache $fetchAdapterCache
	 */
	public function setFetchAdapterCache(FetchAdapterCache $fetchAdapterCache)
	{
		$this->fetchAdapterCache = $fetchAdapterCache;
	}
}