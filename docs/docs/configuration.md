# Configuration Object

The configuration object is essential to properly work with the GraphQL framework. It is strongly suggested to read [Getting Started](getting-started.md) to ensure you do not get lost from here on out.

## Configurable fields

### Controllers directory & namespace

Configurable with `setControllersDirectory` and `setControllersNamespace`. Tells the GraphQL framework where the controllers are located at.

### Generated schema directory & namespace

Configurable with `setGeneratedSchemaDirectory` and `setGeneratedSchemaNamespace`. The schema must be generated with the GraphQL generator tool from an existing `graphqls` file.

### Logger configuration

Configurable with `setLogger`. It must be a PSR-4 compliant logger instance. If unset, no logging actions will be taken by GraphQL. Verbosity is assumed to be handled by the logger output.

### Development environment configuration

Configuration with `setInDevMode`. If true, caches will be disabled and better exceptions will be displayed to aid in development. As these errors have a performance overhead, it is strongly recommended to disable the development mode in production.