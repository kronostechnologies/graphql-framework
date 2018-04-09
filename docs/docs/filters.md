# Filters

Filters are applied directly to `FetchAdapters`. If creating your own Fetch Adapter, a custom filter based on `FilterInterface` should also be created.

## DoctrineFilterInterface

These filters are applied directly to a Doctrine `QueryBuilder`. Simply extending the `DoctrineFilterInterface` and detailing the function `applyToQueryBuilder` suffices.

Example filter:
```
class IdentifierInFilter implements DoctrineFilterInterface
{
    protected $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }
    
    public function applyToQueryBuilder(QueryBuilder $queryBuilder)
    {
        return $queryBuilder->where($queryBuilder->expr()
            ->in('id', $this->ids)
        );
    }
}
```

## ArrayFilterInterface

These filters are applied directly to an array. The dataset is already loaded in memory at that point, and the filters only need to filter out the unnecessary array values.

Example filter:
```
class IdentifierInFilter implements ArrayFetchFilterInterface
{
    protected $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    public function filterArrayResults(array $value)
    {
        return array_filter($value, function ($entry) {
            return in_array($entry['id'], $this->ids);
        });
    }
}
```