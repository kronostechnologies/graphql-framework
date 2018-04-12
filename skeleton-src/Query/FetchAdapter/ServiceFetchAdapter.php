<?php


/**
 * A fetch adapter closely link to another service.
 */
class ServiceFetchAdapter implements FetchAdapterInterface
{

	/**
	 * Fetch and return multiple results from the underlying abstraction layer.
	 *
	 * @return mixed[]
	 */
	public function fetch()
	{
		// TODO: Implement fetch() method.
	}

	/**
	 * Fetch and return the first result from the fetch function, or returns null.
	 *
	 * @return mixed|null
	 */
	public function fetchOne()
	{
		// TODO: Implement fetchOne() method.
	}

	/**
	 * Applies a filter to the current fetch adapter to be used with fetchAll.
	 *
	 * @param FilterInterface $filter
	 */
	public function applyFilter(FilterInterface $filter)
	{
		// TODO: Implement applyFilter() method.
	}
}