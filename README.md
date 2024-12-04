# ArrayHelper
- Efficient and fast processing of an associative field or an array of objects

## Example collection data
- Types of collection data is array or array of objects
```
$data = [
    [
        'id' => 2011,
        'name' => 'John Doe',
        'age' => 30,
        'email' => 'john.doe@example.com',
        'address' => [
            'street' => '123 Main St',
            'city' => 'Springfield',
            'zip' => '12345'
        ],
        'preferences' => [
            'newsletter' => false,
            'notifications' => false
        ],
        'isActive' => true
    ],
    ...
];
```

### ArrayHelper::map(array $data, string|Closure $from, string|Closure $to, string|Closure|null $group = null)
- The goal is to efficiently create custom array structure

> [!TIP]
> **ArrayHelper::map($data, 'id', '__item__');** // return same array indexed by ID

> [!TIP]
> **ArrayHelper::map($data, 'id', 'email, age, address.city, address.zip, isActive, address');**
- Extract values multidimensional array with '.'
```
[
    2011 => [
        'email' => 'john.doe@example.com',
        'age' => 30,
        'city' => 'Springfield',
        'zip' => '12345'        
        'isActive' => true,
        'address' => [
            'street' => '456 Oak Ave',
            'city' => 'Mapleton',
            'zip' => '67890'
        ],
    ],
    ...
];
```
### Example of another using

> [!TIP]
> **ArrayHelper::map($data, 'id', function($item) { return $item['age'] * 2; })**

> [!TIP]
> **ArrayHelper::map($data, '_', 'email, age','name');** // return numeric indexed array with values grouped by 'name'

> [!TIP]
> **ArrayHelper::mapToString(array $data, string|Closure $from, string|Closure $to, string|Closure|null $group = null, string $separate = ',')**
- The goal is to quickly get an array of strings from a array

> [!TIP]
> **ArrayHelper::mapToString($data, '_', 'email, age');**
- Return indexed array with string value separeted with comma
```
[
  0 => 'john.doe@example.com,30',
  ...
];
```
> [!TIP]
> **ArrayHelper::mapToString($data, 'id', 'email, age', null,'|');**
- Return array indexed by ID with value string separeted by pipeline
```
[
  2011 => 'john.doe@example.com|30',
  ...
];
```
