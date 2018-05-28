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

## Custom context

It will become quite essential to provide a custom context object to the GraphQL framework. A custom context includes element that are agnostic to the framework, but that are still needed to make your application work correctly. For example:

* The authentication context
* Core services context (database, logging)
* Service locators

```
<?php
$config = new FrameworkConfiguration();

// Custom context definition
class CustomContext {
    public $username;
    public $userId;
}

// Initialize custom context
$context = new CustomContext();
$context->username = 'test';
$context->userId = 1;

// Set it in framework configuration
$config->setCustomContext($context);

// Initialize framework from config
// ...
];
```

### Accessing the custom context

The custom context can be accessed from the controller.

```
<?php
class Controller extends BaseController
{
    public function getField() 
    {
        // Fetch custom context. Also, set doccomment to provide autocompletion of it.
        /** @var CustomContext $context */
        $context = $this->context->getConfiguration()->getCustomContext();
        
        // User ID: 1, Username: test
        echo "User ID: {$context->userId}, Username: {$context->username}";
    }
}
```

## Middlewares

Middlewares can alter GraphQL requests and response processed through the framework. Refer to the relevant [Middleware documentation](middleware.md) for more details on how to define them.

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
