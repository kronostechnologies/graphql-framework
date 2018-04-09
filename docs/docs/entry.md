# Entry Point

The entry points are used for initializing a call to the GraphQL framework. Queries can come from multiple sources, such as an HTTP request or an inner service call. Entry points provide a way of calling the inner GraphQL framework services without having to deal with trivial initialization procedures. They can also provide helpful exception-wrapping.

## HttpEntryPoint

This entry point takes a standardized HTTP request (PSR-7) as its initial values, and returns a PSR-7 HTTP response. The configuration object must also be passed here.

```php
// Setup configuration
$configuration = new GraphQLConfiguration();
// ...

// Fetch PSR-7 Request
$request = Request::fromGlobals();

// Execute from EntryPoint
$entryPoint = new HttpEntryPoint($configuration);
$response = $entryPoint->executeQuery($request);
```

Or with the short-hand function:
```php
// Set $configuration and $request...
$response = HttpEntryPoint::executeQueryWithConfig($request, $configuration);
```

## RawQueryEntryPoint

Useful if you need to call the GraphQL service from your inner services. This entry point takes as input the query itself and the parameters, returning the raw response string as a result.

```php
// Set $configuration, $query and $arguments...
$response = RawQueryEntryPoint::executeQueryWithConfig($query, $arguments, $configuration);
```