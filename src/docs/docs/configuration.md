# Configuration Object

The configuration object is an essential part of the framework. Access to it is provided to the underlying controllers, and essentially your whole execution context afterwards.

## Required configuration

The following snippet of code outlines the required configuration object.

```
<?php

$configuration = FrameworkConfiguration::create()
    // Directory which contains your controllers   
    ->setControllersDirectory("./graphql/Controllers")
    // Directory which contains your generated schema
    ->setGeneratedSchemaDirectory("./graphql/Schema");
```

## Additional configuration

```
<?php

$configuration = FrameworkConfiguration::create()
    // Enables development mode. Refer to doc section for more info.
    ->enableDevMode()
    // Sets a PSR-3 logger interface to be used by the framework itself.
    ->setLogger($logger);
```

## Dependency injection

Dependency injection is available for the controllers created through the framework. Refer to the [Dependency Injection](dependency-injection.md) to know more.

## Middlewares

Middlewares can alter GraphQL requests and response processed through the framework.

They can be set globally through the configuration object:
```
<?php
$configuration = FrameworkConfiguration::create();

// Initialize a dummy middleware
$relayMiddleware = new RelayMiddleware('id');

// Add then remove middleware
$configuration->addMiddleware($relayMiddleware);
$configuration->removeMiddleware($relayMiddleware);
``` 
