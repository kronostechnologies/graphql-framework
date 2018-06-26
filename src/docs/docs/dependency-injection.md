# Dependency injection

Behind the scenes, GraphQL Framework uses dependency injection through [php-di](http://php-di.org/).

## Basics

I highly recommend reading about dependency injection through PHP-DI's pages:

- [Understanding dependency injection](https://github.com/PHP-DI/PHP-DI/blob/5.4/doc/understanding-di.md)
- [Documentation for PHP-DI 5.4](https://github.com/PHP-DI/PHP-DI/tree/5.4/doc)
- [Container configuration](https://github.com/PHP-DI/PHP-DI/blob/5.4/doc/container-configuration.md)

By default, annotations wiring is enabled.

## Container builder

On the creation of the configuration, the container builder can be fetched in order to configure internal dependencies of your applications, such as a custom context, database connections, or service classes.

## Injection on controllers

The controllers are created by fetching the entry of the dependency injection container. As such, instance variables can be configured to be injected by the DI container. Example:

```
<?php

// ...imports

class QueryController extends BaseController
{
    /**
     * $this->externalService will contain an instance of ExternalService.
     *    
     * @Inject
     * @var ExternalService
     */
     protected $externalService;    
}
```
