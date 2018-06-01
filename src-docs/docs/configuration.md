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

Needs arise to pass information through the framework back to your business logic. A custom context includes elements that are agnostic to the framework, but that are still needed to make your application work correctly. For example:

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
```

You can define any class you want as your custom context. Just be aware that it can only be set in the initial configuration (but the internals of it can be modified later down the road).

### Accessing the custom context

The custom context can then be accessed from the controller.

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
