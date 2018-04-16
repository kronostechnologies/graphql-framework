# Cache

The GraphQL framework handles 4 cache levels from the get-go. Turning on developer mode in the configuration will fully disabled all **persistent** cache layers (levels 1 and 2 for now), so they are effectively only active in production.

A cache adapter must be configured in order to enable the cache in production.

## Levels

### Level 1: Type Registry cache

In particular, this caches the available types from an `AutomatedTypeRegistry`. After the first request is done, this cache is built and will prevent listing all files under the generated schema directory.

This cache level **NEEDS TO** be persisted between requests.

A type registry cache is created each time the controllers directory or namespace changes.

### Level 2: Controllers cache

The controllers cache handle the following:

1. Listing available controllers under the specified directory.
2. Fetching the controller name from its type name.

By enabling the cache, the development mode is also disabled. This prevents development help actions from happening. For more information about this, see the [Development Mode](development-mode.md) section.

This cache level **NEEDS TO** be persisted between requests.

### Level 3: Resolve cache

Assuming a resolve function is called with the same arguments as a previous call in the same query, the resolve cache will return the result it has in cache instead of calling the same resolve function over again.

This cache level **IS NOT** persisted between requests.

### Level 4: Fetch adapter cache

Assuming a fetch adapter call is the very same as a previous call in the same query, the fetch adapter cache will return the result it has in cache instead of fetching the results over again.

This cache level **IS NOT** persisted between requests.

## Persistence

The cache levels 1 & 2 absolutely needs to be persisted between requests, or they simply add a useless layer of processing to the request. If they cannot be enabled for any reason, you can disable them through the [GraphQLConfiguration object](configuration.md).