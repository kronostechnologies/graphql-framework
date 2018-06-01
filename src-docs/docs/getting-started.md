# Getting started

Beforehand, it is important to know that this library does not provide an out-of-the-box solution. As such, some amount of PHP knowledge is required, and ideally a Web framework should encapsulate the GraphQL endpoint. One such as [Laravel](https://laravel.com), [Slim](https://www.slimframework.com/) or [Symfony](https://symfony.com/). All you truly need is a way to read and write PSR-7 objects from/to the client. Afterwards, you can handle incoming HTTP requests as GraphQL requests.

## Requirements

* A project with an initialized composer.json
* A project with an initialized Gruntfile and package.json
* Some way to read and write PSR-7 request objects to/from the client
* An endpoint dedicated to GraphQL

## Implementation

Assuming you have all three things above, the first thing to do would be to include both the GraphQL Generator and the GraphQL Framework.

```
composer require kronostechnologies/graphql-framework
composer require --dev kronostechnologies/graphql-generator
```

Once this is done, your PHP dependencies are setup. Next, we need a package from npm in order to generate the GraphQL schema for us:

```
npm install --save-dev grunt-graphql-php-generator
```

## Setting up a schema watcher

Once this is installed, you can create your first `.graphqls` schema. Let's create it at a specific location to simplify the example, under `graphql/schema.graphqls`:

```
scalar DateTime
scalar Cursor

interface Identifiable {
	id: ID
}

enum Color {
	BLUE,
	RED,
	YELLOW,
	GREEN,
	PURPLE,
	CYAN
}

type Item implements Identifiable {
	id: ID,
	name: String,
	color: Color
}

type Query {
	item(id: ID): Item!
	items(page: Int, perPage: Int): [Item!]!
}
```

Once created, you will need to adjust your Gruntfile to tell it from which file to generate the schema from, and where to. Add this to it:

```
    'autogen-schema': {
        options: {
            source: './graphql/schema.graphqls',
            destination: './[BaseFolder]/GraphQL/Schema/',
            generatorCmdPath: './vendor/bin/graphqlgen',
            namespace: '[BaseNamespace]',
            deleteAndRecreate: true,
            runPHPCSFixer: true
        }
    }
```

Now, you can run a Grunt command to generate the files required by the framework for you:

```
grunt autogen-schema
```

## Entry point

The entry point requires access to a PSR-7 request object, and it will respond in a PSR-7 response. The core requirement to handle a GraphQL is the following:

```
$configuration = GraphQLConfiguration::create()
    ->setControllersDirectory(__DIR__ . '\\[BaseNamespace]\\GraphQL\\Controllers')
    ->setGeneratedSchemaDirectory(__DIR__ . '\\[BaseNamespace]\\GraphQL\\GeneratedSchema');

// Assume $request contains the PSR-7 request.
$entryPoint = new HttpEntryPoint($configuration);
$response = $entryPoint->executeRequest($request);

// $response contains the PSR-7 response
```

You should now be able to query the GraphQL entry point. It will give out its introspection result, but it won't execute any query successfully since we have defined no controller yet.

## Query controller

Let's define a sample query controller to get a single item. It should be located under `[BaseDirectory]\GraphQL\Controllers`. Since we want to get the `item` field in the `Query` type defined in the schema higher up, we need a `QueryController`:

```
<?php

class QueryController extends BaseController {
    public function getItem() {
        return $this->hydrator->fromSimpleArray(ItemDTO::class, [
            'id' => $this->context->getArgument('id'),
        ];
    }
}
```

Here, we simply return an `ItemDTO`, which is the representation of what querying `Item` in an object. These DTOs are made by the generator to aid in development mostly.

Now, the following query should work:

```
query {
    item(id: 1) {
        id
    }
}
```