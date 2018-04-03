<?php

/**
 * Defines a filter which can be applied on an ArrayFetchAdapter.
 */
interface ArrayFetchFilterInterface extends FilterInterface
{
    public function filterArrayResults(array $value);
}