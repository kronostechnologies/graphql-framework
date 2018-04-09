# Configuration Object

The configuration object is essential to properly work with the GraphQL framework. It is strongly suggested to read [Getting Started](getting-started.md) to ensure you do not get lost from here on out.

## Basic configuration

### Controllers directory & namespace

Configurable with `setControllersDirectory` and `setControllersNamespace`. Tells the GraphQL framework where the controllers are located at.

### Generated schema directory & namespace

Configurable with `setGeneratedSchemaDirectory` and `setGeneratedSchemaNamespace`. The schema must be generated with the GraphQL generator tool from an existing `graphqls` file.

### Logger configuration

Configurable with `setLogger`. It must be a PSR-4 compliant logger instance. If unset, no logging actions will be taken by GraphQL. Verbosity is assumed to be handled by the logger output.

### Development environment configuration

Configuration with `setInDevMode`. If true, caches will be disabled and better exceptions will be displayed to aid in development. As these errors have a performance overhead, it is strongly recommended to disable the development mode in production.

## Cache configuration

The cache layers are only active in a production environment. However, if it is not configured, it will be not be used at all, so beware, as the Type registry and Controllers caches play a crucial role in the optimization of the framework. For more information about cache layers, see the [Cache page](cache.md).

### Persistent cache configuration

The persistent cache is used to store information about the Type registry and Controllers caches.

A PSR-6 cache adapter is supported in the configuration by calling the function `setCacheAdapter`. If this adapter is not set, the cache layers 1 & 2 won't work.

### Per-cache configuration

It is possible to disable an individual cache layer if required:
- Type Registry Cache (Level 1): `setIsTypeRegistryCacheEnabled`
- Controllers Cache (Level 2): `setIsControllersCacheEnabled`
- Resolve Cache (Level 3): `setIsResolveCacheEnabled`
- Fetch adapter Cache (Level 4): `setIsFetchAdapterCacheEnabled`

Although the caches may appear enabled, the development mode overrides all caches and disable them.




