# Fetch Adapters

Fetch adapters are used to obtain data from an underlying data source, be it a database or a service layer.

## Database fetch adapter (DoctrineFetchAdapter)

Although you will eventually want to decouple your application from your database, a fetch adapter closely linked to it is a good start. The `DoctrineFetchAdapter` allows linking to a relational database supported by the Doctrine ORM.

Filters specified in the `DoctrineFetchAdapter` must be of type `DoctrineFilterInterface`. These filters apply to a `QueryBuilder`. Upon calling fetch, the results are taken and kept in a local cache.