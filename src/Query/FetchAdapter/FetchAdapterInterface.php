<?php

/**
 * Bridges a GraphQL entity to the internal logic of an application.
 * This receives the source GraphQL query arguments and expects a correctly built DTO
 * for each of its functions.
 *
 * The adapter should be able to apply filters required for its types.
 */
interface FetchAdapterInterface
{
    /**
     * Fetch and return multiple results from the underlying abstraction layer.
     *
     * @return mixed[]
     */
    public function fetch();

    /**
     * Fetch and return the first result from the fetch function, or returns null.
     *
     * @return mixed|null
     */
    public function fetchOne();

    /**
     * Applies a filter to the current fetch adapter to be used with fetchAll.
     *
     * @param FilterInterface $filter
     */
    public function applyFilter(FilterInterface $filter);
}