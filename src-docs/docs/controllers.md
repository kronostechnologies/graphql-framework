# Controllers

Controllers act as automatic resolvers for the incoming GraphQL queries. 


## Object type resolution 

Each time a detailed field is queried under a type, the controller name is first resolved through the type name, in the format `{TypeName}Controller`. It is auto-detected from the provided controllers directory recursively. As type names are unique in GraphQL, no controller name collision will never occur. Object type controllers **need to** extend `BaseController`.

Once the matching controller is found, a function matching the detailed field is then sought in the format `get{FieldName}`. The function is then called, and its result returned to be processed by the middleware, and ultimately to be returned to the original client.

### Context

The context provided to the controllers contain many helpful objects. It is accessed through `$this->context`.

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

### Hydrators

Another special field can be accessed with `$this->hydrator` in the controllers. Hydrators can convert an array to any DTO object from a given definition.

#### Transforming an array into a DTO (no sub-object)

```
<?php

class TargetDTO 
{
    public $id;
    public $name;
}

$array = [
    'id' => 1,
    'name' => 'test',
];

// Do work here
$arrayDTO = $this->hydrator->fromSimpleArray(TargetDTO::class, $array);
// Contains TargetDTO { id = 1, name = 'test' }
```

#### DTO definitions

DTO definitions are only necessary when you are dealing with a multi-level array to transform into a DTO.

Definition with field $b being a sub-object.
```
<?php
class A
{
    public $a;
    public $b; // Contains B instance
}

class B
{
    public $c;
    public $d;
}

class ABDTODefinition extends BaseDTODefinition
{
    public function __construct()
    {
        // Short-hand definition
        $this->dtoDefinition = [
            'root' => A::class,
            'fields' => [
                'b' => B::class,
            ]
        ];
        
        // ... or long definition (would be useful if 'b' had additional sub-DTOs)
        $this->dtoDefinition = [
            'root' => A::class,
            'fields' => [
                'b' => [
                    'fqn' => B::class,
                    // 'fields' => [ 'c' => C::class, ]
                ]
            ]
        ];
    }
}
```

#### Applying a custom DTO definition

Once your DTO definition is created, you can apply it to an array with `fromDTODefinition($definition, $values).

```
<?php
// Continuing from the sample higher up
$values = [
    'a' => 1,
    'b' => [
        'c' => true,
        'd' => 'aaa',
    ]
];

// Do work here
$this->hydrator->fromDTODefinition(new ABDTODefinition(), $values);
// Contains A { a = 1, b = B { c = true, d = 'aaa' } }

// If we were to use fromSimpleArray instead, we would obtain a completely different result
$arrayDTO = $this->hydrator->fromSimpleArray(A::class, $values);
// Contains A { a = 1, b = [ 'c' => true, 'd' => 'aaa' ] }
```

## Scalar type resolution

Finding the correct scalar type controller is no different than finding an object type one. It needs to be named `{TypeName}Controller`.

## Scalar types

The scalar types controllers defined in the application should derive a special controller called `ScalarTypeController`. They are not associated with the base controller by itself and they have no access to the application context directly. Scalar type controller **need to** extend `ScalarController`.

All methods that must be implemented by it are found in the scalar controller:

* `serializeScalarValue($value)`: For outgoing requests. Takes `$value` and converts it into a format that is to be read by the client.
* `getScalarFromValue($value)` & `getScalarFromLiteral($literalValue)`: From incoming requests arguments. Takes `$value` and converts it into a format that is going to be processed by the controller. Both functions should return the same value.

## Interface types

Interface types controllers derive from the `InterfaceController`. It contains a single function called `resolveInterfaceType($value)`. This function is executed when a response is sent to the client, and `$value` is encapsulated in a DTO at this point. This should return the type name of `$value` as a string known by the `TypeStore`. 
