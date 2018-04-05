# Controllers

To the underlying PHP library, controllers are resolvers with enhanced automation capability. They are automatically found from the `QueryResolver`, and which directory & namespace to seek can be configured directly in the initial `GraphQLConfiguration` passed to the service with `setControllersNamespace` and `setControllersDirectory`. 