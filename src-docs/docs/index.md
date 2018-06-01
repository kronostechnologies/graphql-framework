# GraphQL Framework

## Overview

- [Schema Generator](generator.md): to generate files to be consumed by [Webonyx GraphQL library](https://github.com/webonyx/graphql-php).
- [Controllers](controllers.md): Classes which are consumed by the framework to resolve paths of a GraphQL query. A controller has access to the initial configuration passed to the framework, along with a request context.
- [Entry Point](entry.md): Translates entry data to be consumed by the underlying library, and return the underlying library result as properly formatted data according to the input source.