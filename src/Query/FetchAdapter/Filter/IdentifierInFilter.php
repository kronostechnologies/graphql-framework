<?php

/**
 * Simple filter which dictates the identifier field should be one of the given ids.
 */
class IdentifierInFilter implements ArrayFetchFilterInterface
{
    protected $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Returns the filter's name.
     *
     * @return string
     */
    public function getName()
    {
        return 'IdentifierInFilter';
    }

    public function filterArrayResults(array $value)
    {
        return array_filter($value, function ($entry) {
            return in_array($entry['id'], $this->ids);
        });
    }
}