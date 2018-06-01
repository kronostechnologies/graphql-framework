# Schema generator

An external library is used to transform a `graphqls` (GraphQL Schema) file into its relevant parts which is essential to get this library to work by itself.

## Grunt tooling

The schema generator does need PHP to be installed locally, along with NodeJS and Grunt. This assumes you have an initialized `package.json`. 

```bash
composer install --dev graphql-generator
npm install --save-dev grunt-graphql-php-generator
```

Afterwards, the generator needs to be configured through the Gruntfile by adding an entry to Grunt's `initConfig` object:

```plaintext
    'autogen-schema': {
        options: {
            source: '{FilePathOfGraphQLSchema}',
            destination: '{GeneratedSchemaDirectory}',
            generatorCmdPath: './vendor/bin/graphqlgen',
            namespace: '{NamespaceOfGraphQLSchema}',
            deleteAndRecreate: {true:if you want to delete and recreate GraphQL files everytime},
            runPHPCSFixer: {true:to code format in PSR-1/2}
        }
    }
```

The generator can then be ran with 

```
grunt autogen-schema
```
