# GraphQL Framework

[![CircleCI](https://circleci.com/gh/kronostechnologies/graphql-framework/tree/master.svg?style=svg)](https://circleci.com/gh/kronostechnologies/graphql-framework/tree/master)

[![Maintainability](https://api.codeclimate.com/v1/badges/198f7b869eba7c71de44/maintainability)](https://codeclimate.com/github/kronostechnologies/graphql-framework/maintainability)

A PHP package acting as a harness around the various PHP GraphQL libraries available. It is closely coupled to Webonyx's GraphQL implementation and adds several layers of abstraction in order to ease the development of a GraphQL application or endpoint.

**DISCLAIMER: Although this uses a solid set of packages and the core architecture is decided, this is not considered production-ready yet. Some important features are missing, such as the fetch adapters described in the documentation.**

## Functionnalities

* Automatically transforms one graphqls file to DTOs and type definitions
* Provides a PSR-7 HTTP entry point to the GraphQL framework
* Types & Controllers (Resolvers) are automatically detected through Reflection

## Documentation

Extensive documentation is available on GitHub pages at https://kronostechnologies.github.io/graphql-framework/
