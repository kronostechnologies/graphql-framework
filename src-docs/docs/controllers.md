# Controllers

Controllers act as automatic resolvers for the incoming GraphQL queries. 

## Naming convention

All controllers should be named `{TypeName}Controller` **and** be located under the controllers directory as defined in the [Required configuration section](configuration.md#required-configuration). Resolution of a controller by name is recursive, so subdirectories can be created under the given directory.

Additionnally, a controller must extend a specific existing one in order to determine which type to use:

* `BaseController`: For [Object types](controllers.md#object-type-resolution)
* `ScalarController`: For [Scalar types](controllers.md#scalar-type-resolution)
* `InterfaceController`: For [Interface types](controllers.md#interface-type-resolution) 

## Context

A context is provided to all controller base implementations. It is accessed through `$this->context`.

* `getConfiguration()`: Returns the configuration initially passed to the framework.
* `getCurrentParentObject()`: Returns the result of the parent call.
* `getArguments()`: Returns all the arguments provided to the level of this query. Always an array, but empty if it was going to be null.
* `getArgument($path)`: Returns an argument by path. For example:
```
<?php
$arguments = [
    'a' => [
        'b' => true
    ]
];

// Can be queried with...
$this->getArgument('a.b'); // Returns true
```
* `getFullQueryString()`: Returns the full query string passed to the client.
* `getIdFromArgument($path)`: Returns the id contained in a Relay argument. Also requires a dot-notation path like `getArgument`.

The context object is immutable, which means the controller cannot modify it by itself. It is altered between every controller call by the inner framework, but it cannot be modified by the controllers themselves.

## Object type resolution 

Derived from the base class `BaseController`.

Once the matching controller is found, a function matching the detailed field is then sought in the format `get{FieldName}`. The function is then called, and its result returned to be processed by the middleware, and ultimately to be returned to the original client.

### Hydrators

Hydrators can convert an array to any DTO object from a given definition. See [Advanced data handling](data.md) for more details.

## Scalar type resolution

The scalar types controllers defined in the application should derive a special controller called `ScalarTypeController`. They are not associated with the base controller by itself and they have no access to the application context directly. Scalar type controller **need to** extend `ScalarController`.

All methods that must be implemented by it are found in the scalar controller:

* `serializeScalarValue($value)`: For outgoing requests. Takes `$value` and converts it into a format that is to be read by the client.
* `getScalarFromValue($value)` & `getScalarFromLiteral($literalValue)`: From incoming requests arguments. Takes `$value` and converts it into a format that is going to be processed by the controller. Both functions should return the same value.

## Interface type resolution

Interface types controllers derive from the `InterfaceController`. It contains a single function called `resolveInterfaceType($value)`. This function is executed when a response is sent to the client, and `$value` is encapsulated in a DTO at this point. This should return the type name of `$value` as a string known by the `TypeStore`. 
