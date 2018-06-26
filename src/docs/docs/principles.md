# Principles & Architecture

This framework is built to be as **simple** as possible to use, preferring a **convenience-over-configuration** approach. Although it is aimed to be entirely modular, using its core built-in features will provide a much better experience.

## Workflow

When working with the library, you should follow this workflow:

1. Create a GraphQL schema to expose.
2. Setup the [Schema generator](generator.md)
3. Create a GraphQL entry point.
4. Create a Controller for each type in the GraphQL schema.
5. Test your API.

See [Getting Started](getting-started.md) for a better starting example.
