<?php

/**
 * Defines a filter to be used by a FetchAdapterInterface.
 */
interface FilterInterface
{
    /**
     * Returns the filter's name.
     *
     * @return string
     */
    public function getName();
}