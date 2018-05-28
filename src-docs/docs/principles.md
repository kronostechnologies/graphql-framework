# Principles & Architecture

This framework is built to be as **simple** as possible to use, preferring a **convenience-over-configuration** approach. Although it is aimed to be entirely modular, using its core built-in features will provide a much better experience.

## Workflow

When working with the library, you should follow this workflow:

1. Create a GraphQL schema to expose.
2. Create a GraphQL entry point.
3. Create a Controller for each type in the GraphQL schema.
4. Test your API.

See [Getting Started](getting-started.md) for a better starting example.

## Architecture

![Full Request Flow](images/request-global.png)

Here is the flow of a query in the framework, cut piece-by-piece.

![Step 1 Request Flow](images/request-step-1.png)

We have received a GraphQL request from a client. The `GraphQLConfiguration` object is built and used throughout the entire framework.

![Step 2 Request Flow](images/request-step-2.png)

The HTTP entry point the takes the configuration and begin executing the query as any other by passing the arguments and query text to the `QueryExecutor`, which acts as the core object of the framework.

![Step 3 Request Flow](images/request-step-3.png)

The `QueryExecutor` executes the query on the auto-generated schema. The Generated Schema then reaches the Query Resolver, which is used to determine the correct controller and controller function to execute.

![Step 4 Request Flow](images/request-step-4.png)

Once the correct controller is determined, its resolve function is executed.

![Step 5 Request Flow](images/request-step-5.png)

The Fetch Adapter is used by the controller to get a specific query result.

![Step 6 Request Flow](images/request-step-6.png)

The Fetch Adapter communicates with a translator to convert the given entities to their target GraphQL DTO correctly.

Finally, the result is returned to the client.
