# Getting Started

Before setting things up, we must make sure all the necessary requirements are setup correctly.

## Requirements

* A GraphQL schema (.graphqls) containing the definition of your types. To learn how to make one, see the official site: [https://graphql.org/learn/schema/](https://graphql.org/learn/schema/)
* A directory and PHP namespace in which to generate the schema types and DTOs (for the underlying GraphQL library).

## Structure

A basic GraphQL project should have the following structure.

```text
\GraphQL
    \Controllers            <-- Controllers
    \FetchAdapters          <-- FetchAdapters
        \Filters            <-- Custom Filters
    \GeneratedSchema        <-- The generated schema PHP files
        \DTOs               <-- (Auto-generated)
        \Types              <-- (Auto-generated)
    \Schema                 <-- Your graphqls file(s)
        main.graphqls       <-- (Sample file name)
```

## Schema Generator

First off, let's use a basic schema to ensure everything is working correctly. We will use one directly from the GraphQL generator repository. Put this in the `Schema/main.graphqls` file:

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

The generator should be ran each time a graphqls file is modified under the `Schema` directory, on all files in that directory. A grunt hook is available to do just that.

```
TODO: Grunt hook
```

Once it is setup, you can install `grunt` [https://gruntjs.com/getting-started](https://gruntjs.com/getting-started). Running `grunt watch:generate-schema` will auto-regenerate the schema PHP files everytime a graphqls file is modified, while also announcing errors to the user.

You can run `grunt generate-schema` to generate it without needing to change a graphqls file.

## Setting up a FetchAdapter

Fetch adapters bridge the database or service layer to GraphQL. In our case, we don't have a database, so we will use the `ArrayFetchAdapter` to make a mock database. This adapter uses a flat array as its data source.

We do not need a `FetchAdapter` for the `Query` type, as it holds no data by itself. Instead, we will make one for the `Item` type which has 3 fields: `id`, `name`, and `color` (an enumeration). Create it under `FetchAdapters\ItemFetchAdapter.php`.

```
TODO: Namespace
TODO: Validate generated ColorEnumType

class ItemFetchAdapter extends ArrayFetchAdapter
{
    public function __construct()
    {
        // Dummy data source
        $this->dataSource = [
            [
                'id' => 1,
                'name' => 'Hello world',
                'color' => 'Red',
            ],
            
            [
                'id' => 2,
                'name' => 'Second entry',
                'color' => 'Yellow',
            ],
            
            [
                'id' => 3,
                'name' => 'Third entry',
                'color' => 'Cyan',
            ],
            
            [
                'id' => 4,
                'name' => 'Last entry',
                'color' => 'Purple',
            ]
        ];
    }
}
```

## Filtering by ID & pagination

Before continuing, we have to take care of a few issues. Our `getField` function filters by ID and return a single result, whereas the `getFields` function takes some pagination arguments. We need to handle filtering on our specific dataset. Let's start with the IDs.

### IdentifierInFilter

```
ToDo: Namespace

class IdentifierInFilter implements ArrayFetchFilterInterface
{
    protected $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    public function filterArrayResults(array $value)
    {
        return array_filter($value, function ($entry) {
            return in_array($entry['id'], $this->ids);
        });
    }
}
```

We specify it is an `ArrayFetchFilterInterface` so the `FetchAdapter` we created earlier can apply the filter to the query correctly.

This one simply uses `array_filter` to check if the entry's id matches one of the ids given to the given.

### PageFilter

```
ToDo: Namespace

class PageFilter implements ArrayFetchFilterInterface
{
    protected $perPage;
    
    protected $page;

    public function __construct($perPage, $page)
    {
        $this->perPage = $perPage;
        $this->page = $page;
    }

    public function filterArrayResults(array $value)
    {
        $pages = array_chunk($value, $this->perPage);
        
        return $pages[$this->page - 1];       
    }
}
```

This filter uses `array_chunk` to separate the values array by pagesize and returns the corresponding index of the page.

## Setting up the QueryController

A controller is directly bound to its type field automatically. For the query controllers, we need to resolve two fields: `item` and `items`. By default, the framework expects controller methods to have a function named `get(FieldNameInCamelCase)`. For resolving `item`, we will have a function named `getItem`, and for resolving `items`, we will have another one named `getItems`. If you forget to create a controller or field in the controller, the framework will inform you through its logger of what actions you should take.

Now for the QueryController itself (`Controllers\QueryController.php`):

```
TODO: Namespace
TODO: IdentifierInFilter, PageFilter

class QueryController extends BaseController
{
    protected $itemFetchAdapter;

    public function __construct(ItemFetchAdapter $itemFetchAdapter)
    {
        $this->itemFetchAdapter = $itemFetchAdapter;
    }
    
    public function getItem($id)
    {
        return $this->itemFetchAdapter
            ->applyFilter(new IdentifierInFilter([$id]))
            ->fetchOne();
    }
    
    public function getItems($page, $perPage)
    {
        return $this->itemFetchAdapter
            ->applyFilter(new PageFilter($page, $perPage))
            ->fetch();
    }
}
```

For `getItem`, we use the `IdentifierInFilter` we created earlier, passing a single ID to it, and fetch the first result from the dataset.

For `getItems`, we use the `PageFilter`, passing our arguments to it, and fetch all the dataset available.

The `ItemFetchAdapter` is reset each time a field is resolved, so don't worry about data integrity here.