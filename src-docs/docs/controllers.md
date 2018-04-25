# Controllers

To the underlying PHP library, controllers are resolvers with enhanced automation capability. They are automatically found from the `QueryResolver`, and which directory & namespace to seek can be configured directly in the initial `GraphQLConfiguration` passed to the service with `setControllersNamespace` and `setControllersDirectory`.

## Mapping

It is assumed a controller manages one entity. 

## Field resolver functions

A function `getFieldMemberQueryFunctionName` is present in `BaseController` for determining the name of the function to use to query a specific member in the controller.

## Resolving steps

1. Which controller to call is determined with the `ControllerMatcher` class.
2. Once determined that the controller exists and is of correct type, the `getFieldMemberQueryFunctionName` function is called.
3. Finally, check to see if the function exists in the given class. If so, call it and use it as a resolver (returning its result to the GraphQL library).

## Scalar types

The scalar types defined in the application should derive a special controller called `ScalarTypeController`. They are not associated with the base controller by itself and they have no access to the application context directly.

They implement 3 functions:

* `serialize($value)`: Which converts from a GraphQL format to a storeable format.
* `getScalarFromValue($value)`: When the value is fetched from a query argument and converted to a GraphQL compatible format.
* `getScalarFromLiteral($literalValue)`: When the value was directly in the query argument and is converted to a GraphQL compatible format.

## Interfaces

Lastly, it is necessary for the underlying GraphQL library to know which type belongs to which implementation of an object. You can implement an `InterfaceController` for each existing interface. It should implement the `getTypeNameForValue($value)` function.