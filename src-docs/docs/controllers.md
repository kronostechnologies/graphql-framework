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