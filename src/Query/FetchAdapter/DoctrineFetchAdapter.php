<?php


class DoctrineFetchAdapter implements FetchAdapterInterface
{
	/**
	 * @var ArrayFetchFilterInterface[]
	 */
	protected $filters = [];

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
	 * @return DoctrineFetchAdapter
	 * @throws Exception
	 */
	public function applyFilter(FilterInterface $filter)
	{
		if ($filter instanceof DoctrineFilterInterface) {
			$this->filters[] = $filter;
		} else {
			$className = self::class;
			$filterFQN = get_class($filter);
			throw new Exception("Filter {$filterFQN} was used in {$className}, but it does not extend DoctrineFilterInterface");
		}

		return $this;
	}
}