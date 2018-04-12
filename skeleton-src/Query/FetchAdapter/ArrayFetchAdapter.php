<?php

/**
 * Simple adapter which uses an array as a data source. Useful for unit testing.
 */
class ArrayFetchAdapter implements FetchAdapterInterface
{
	use FetchAdapterCacheAwareTrait;

    /**
     * @var array
     */
    protected $dataSource = [];

    /**
     * @var ArrayFetchFilterInterface[]
     */
    protected $filters = [];

	/**
	 * @param array $dataSource
	 */
    public function __construct(array $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * Fetch and return multiple results from the underlying abstraction layer.
     *
     * @return mixed[]
     */
    public function fetch()
    {
        $filteredResults = $this->dataSource;

        foreach ($this->filters as $filter) {
            $filteredResults = $filter->filterArrayResults($filteredResults);
        }

        return $filteredResults;
    }

    /**
     * Fetch and return the first result from the fetch function, or returns null.
     *
     * @return mixed|null
     */
    public function fetchOne()
    {
        $results = $this->fetch();

        $firstResult = array_shift($results);

        return $firstResult;
    }

	/**
	 * Applies a filter to the current fetch adapter to be used with fetchAll.
	 *
	 * @param FilterInterface $filter
	 * @return ArrayFetchAdapter
	 * @throws Exception
	 */
    public function applyFilter(FilterInterface $filter)
    {
        if ($filter instanceof ArrayFetchFilterInterface) {
            $this->filters[] = $filter;
        } else {
            $className = self::class;
			$filterFQN = get_class($filter);
            throw new Exception("Filter {$filterFQN} was used in {$className}, but it does not extend ArrayFetchFilterInterface");
        }

        return $this;
    }
}