# Advanced data handling

When receiving data from the underlying GraphQL library, it is received in an array format, like so:

```
$args = [
    'fieldOne' => 1,
    'fieldTwo' => 2
];

// Get simple fields from args
$fieldOneVal = $args['fieldOne'];
$fieldTwoVal = $args['fieldTwo'];

// The field might be set, but not always
$fieldThreeVal = array_key_exists($args['fieldThree']) ? $args['fieldThree'] : null;
```

Along with creating a lot of code just for the fetching logic, the latter case can become problematic assuming a scalar type can also be given a null value through the GraphQL API. Also, a distinction needs to be made between `User did not set field` and `User forcefully set field to null`, which is not possible with this code.

## Hydrators

Hydrators allow an array data structure to be translated into a DTO.

By calling `$this->hydrator->fromSimpleArray($dtoFQN, $data)` in a controller, you can create a DTO instance filled with values. Undefined values by the user are set to `UndefinedValue`.

Example:


```
// DTO Code
public class SampleDTO 
{
    public $fieldOne;
    public $fieldTwo;
    public $fieldThree;
}

// (Arguments passed to query)
$args = [
    'fieldOne' => 1,
    'fieldTwo' => null,
];

// Controller function code (from arguments)
/** @var SampleDTO $dto */
$dto = $this->hydrator->fromSimpleArray(SampleDTO::class, $this->getContext()->getCurrentArguments());
```

Result:
```
$dto->fieldOne === 1;
$dto->fieldTwo === null;
$dto->fieldThree instanceof UndefinedValue;
```

## Nested DTOs

Sometimes, it is necessary to nest DTOs. The hydrators can be provided with a class extending `BaseDTODefinition`. This class contains an array definition of the DTO, describing what is to be nested or not. 

The class should look as follows:

```
class DTODefinitionName
{
    public function __construct()
    {
		$this->dtoDefinition = [
			'fqn' => RootDTO::class, // ClassName of the root DTO
			'fields' => [ // Only put fields with a depth in here
				'subField' => DepthSubDTO::class, // 'fieldNameInRootDTO' => dtoContainedInFieldClassName
				'subField2' => [ // Deep nesting
				    'fqn' => DeepNestedDTO::class, // Same as root definition
				    'fields' => [ // Once again, only put fields with a depth in here
				        'lastDTO' => LastDTO::class, // Same as before, 'fieldNameInRootDTO' => dtoContainedInFieldClassName,
				        // ... Additional nesting levels work like the initial one
				    ]
				]
			]
		];
    }
}
```

Afterwards, you can pass the DTO definition class name in a controller:

```
// $data being the input array
$dto = $this->hydrator->fromDTODefinition(DTODefinitionName::class, $data);
```

The returned DTO will be filled with nested values. Be careful as an unset nested value will return an `UndefinedValue` in the DTO instead of the initially wanted DTO.
