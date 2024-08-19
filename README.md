<h1 style="text-align: center">abbasghasemi/collection</h1>

<p style="text-align: center">
    <strong>A PHP library for work with arrays</strong>
</p>

## Powerful

- Support List, Map, Set, Stack, Queue, Object ...
- Functional (indexOf, map, where, reduce, first, firstOrNull firstWhere, ...)
- Type generic documentation
- Collections class for generate & filled List `Collections::filled(int $length, mixed $element)`
- The ability to enforce maps and lists to accept a specific type

## Installation

The preferred of installation is via [Composer](https://getcomposer.org). Run the following
command to install the package and add it as a requirement to your project's
`composer.json`:

```bash
composer require abbasghasemi/collection
```

## Array example

```PHP
$list = Collections::filled(10, 'filled'); // ArrayList
echo count($list); // 10
echo Collections::toArraySet($list)->size(); // 1
$list->add(1); // This method does not exist!
echo $list->first(); // filled

$list = new MutableArrayList(/*[default]*/type: 'int');
$list->add(1); // added
$list->add(5); // added
echo count($list); // 2
echo $list->indexOf(1); // 0
echo $list->indexOf(6); // -1

$list = Collections::generate(10, function ($index){
    return $index;
}); // ArrayList
$list = $list->where(function ($element){
    return $element % 2 === 0;
});
echo $list->last(); // 8
echo $list; // [0,2,4,6,8]
```

## Map example

```PHP
$map = new MutableInsensitiveMap();
$map['InSensitive'] = 'Insensitive';
$map['INSENSITIVE'] = 'Insensitive';
echo $map['insensitive']; // Insensitive
echo $map->size(); // 1

$object = new MutableArrayList(); // or other object
$map2 = new MutableObjectMap(keyType: $object::class, valueType: $map::class);
$map2[$object] = $map;

echo intval($map2[$object] === $map); // 1

$map2->values()->first()['INSENSITIVE'] = 10;
echo $map['InSensitive']; // 10
$map2->keys()->first()->add('first');
$map2->keys()->first()->add('last');

echo $object->join(','); // first,last

```

## Arrays class

| Class      | ArrayList | MutableArrayList | ArraySet | MutableArraySet |
|------------|-----------|------------------|----------|-----------------|
| Editable   | ❌         | ✅                | ❌        | ✅               |
| Repeatable | ✅         | ✅                | ❌        | ❌               |
| Iterator   | ✅         | ✅                | ✅        | ✅               |
| Countable  | ✅         | ✅                | ✅        | ✅               |

## Class methods

| Method         | ArrayList | MutableArrayList | ArraySet | MutableArraySet |
|----------------|-----------|------------------|----------|-----------------|
| forward        | ✅         | ✅                | ✅        | ✅               |
| back           | ✅         | ✅                | ✅        | ✅               |
| size           | ✅         | ✅                | ✅        | ✅               |
| isEmpty        | ✅         | ✅                | ✅        | ✅               |
| isNotEmpty     | ✅         | ✅                | ✅        | ✅               |
| contains       | ✅         | ✅                | ✅        | ✅               |
| toArray        | ✅         | ✅                | ✅        | ✅               |
| forEach        | ✅         | ✅                | ✅        | ✅               |
| first          | ✅         | ✅                | ✅        | ✅               |
| firstOrNull    | ✅         | ✅                | ✅        | ✅               |
| last           | ✅         | ✅                | ✅        | ✅               |
| lastOrNull     | ✅         | ✅                | ✅        | ✅               |
| get            | ✅         | ✅                | ✅        | ✅               |
| getRange       | ✅         | ✅                | ✅        | ✅               |
| take           | ✅         | ✅                | ✅        | ✅               |
| firstWhere     | ✅         | ✅                | ✅        | ✅               |
| where          | ✅         | ✅                | ✅        | ✅               |
| lastWhere      | ✅         | ✅                | ✅        | ✅               |
| reduce         | ✅         | ✅                | ✅        | ✅               |
| indexOf        | ✅         | ✅                | ✅        | ✅               |
| lastIndexOf    | ✅         | ✅                | ✅        | ✅               |
| indexWhere     | ✅         | ✅                | ✅        | ✅               |
| lastIndexWhere | ✅         | ✅                | ✅        | ✅               |
| join           | ✅         | ✅                | ✅        | ✅               |
| fillRange      | ❌         | ✅                | ❌        | ❌               |
| update         | ❌         | ✅                | ❌        | ✅               |
| reversed       | ❌         | ✅                | ❌        | ✅               |
| shuffle        | ❌         | ✅                | ❌        | ✅               |
| add            | ❌         | ✅                | ❌        | ✅               |
| addAll         | ❌         | ✅                | ❌        | ✅               |
| insert         | ❌         | ✅                | ❌        | ✅               |
| insertAll      | ❌         | ✅                | ❌        | ✅               |
| remove         | ❌         | ✅                | ❌        | ✅               |
| removeFirst    | ❌         | ✅                | ❌        | ✅               |
| removeLast     | ❌         | ✅                | ❌        | ✅               |
| removeRange    | ❌         | ✅                | ❌        | ✅               |
| removeWhere    | ❌         | ✅                | ❌        | ✅               |
| sort           | ❌         | ✅                | ❌        | ✅               |
| clear          | ❌         | ✅                | ❌        | ✅               |

## Maps class

| Class       | StringMap | MutableStringMap | InsensitiveMap | MutableInsensitiveMap | ObjectMap | MutableObjectMap |
|-------------|-----------|------------------|----------------|-----------------------|-----------|------------------|
| Editable    | ❌         | ✅                | ❌              | ✅                     | ❌         | ✅                |
| Repeatable  | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |
| Iterator    | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |    
| Countable   | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |    
| Insensitive | ❌         | ❌                | ✅              | ✅                     | -         | -                |    
| Object key  | ❌         | ❌                | ❌              | ❌                     | ✅         | ✅                |    

## Class methods

| Method                      | StringMap | MutableStringMap | InsensitiveMap | MutableInsensitiveMap | ObjectMap | MutableObjectMap |
|-----------------------------|-----------|------------------|----------------|-----------------------|-----------|------------------|
| size                        | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |
| isEmpty                     | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |
| isNotEmpty                  | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |    
| forEach                     | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |    
| containsKey                 | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |    
| containsValue               | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |    
| keys                        | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |    
| values                      | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |    
| mutableValues               | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |    
| entries                     | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |    
| entryKey                    | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |    
| get                         | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |    
| *get syntax* \[key]         | ✅         | ✅                | ✅              | ✅                     | ✅         | ✅                |
| toMap                       | ✅         | ✅                | ✅              | ✅                     | ❌         | ❌                |    
| put                         | ❌         | ✅                | ❌              | ✅                     | ❌         | ✅                |    
| *set syntax* \[key] = value | ❌         | ✅                | ❌              | ✅                     | ❌         | ✅                |    
| putIfAbsent                 | ❌         | ✅                | ❌              | ✅                     | ❌         | ✅                |    
| putAll                      | ❌         | ✅                | ❌              | ✅                     | ❌         | ✅                |    
| update                      | ❌         | ✅                | ❌              | ✅                     | ❌         | ✅                |    
| updateKey                   | ❌         | ✅                | ❌              | ✅                     | ❌         | ✅                |    
| replace                     | ❌         | ✅                | ❌              | ✅                     | ❌         | ✅                |    
| merge                       | ❌         | ✅                | ❌              | ✅                     | ❌         | ✅                |    
| remove                      | ❌         | ✅                | ❌              | ✅                     | ❌         | ✅                |    
| removeWhere                 | ❌         | ✅                | ❌              | ✅                     | ❌         | ✅                |    

## Collections class

| Static method      | Description                                                                                      |
|--------------------|--------------------------------------------------------------------------------------------------|
| filled             | `Collections::filled(int $length, mixed $element, ?string $type = null): ArrayList`              |
| generate           | `Collections::generate(int $length, callback $callback, ?string $type = null): ArrayList`        |
| of                 | `Collections::of(mixed ...$elements): ArrayList`                                                 |
| typeOf             | `Collections::typeOf(string $type, mixed ...$elements): ArrayList`                               |
| mutableFilled      | `Collections::filled(int $length, mixed $element, ?string $type = null): MutableArrayList`       |
| mutableGenerate    | `Collections::generate(int $length, callback $callback, ?string $type = null): MutableArrayList` |
| mutableOf          | `Collections::of(mixed ...$elements): MutableArrayList`                                          |
| mutableTypeOf      | `Collections::mutableTypeOf(string $type, mixed ...$elements): MutableArrayList`                 |
| toArrayList        | `Collections::toArrayList(Collection $collection): ArrayList`                                    |
| toMutableArrayList | `Collections::toMutableArrayList(Collection $collection): MutableArrayList`                      |
| toArraySet         | `Collections::toArraySet(Collection $collection): ArraySet`                                      |
| toMutableArraySet  | `Collections::toMutableArraySet(Collection $collection): MutableArraySet`                        |
| sortAscending      | `Collections::sortAscending(MutableCollection $collection): void`                                |
| sortDescending     | `Collections::sortDescending(MutableCollection $collection): void`                               |
| equals             | `Collections::equals(?ObjectC $a, ?ObjectC $b): bool`                                            |
| hashCode           | `Collections::hashCode(mixed $value): int`                                                       |
| toString           | `Collections::toString(mixed $value): string`                                                    |

## See also easy-data-model

[Creates a data model from array data.](https://github.com/abbasghasemi/easy-data-model)

## Author & support

This library was created by [Abbas Ghasemi](https://farasource.com/).

You can report issues at the [GitHub Issue Tracker](https://github.com/abbasghasemi/collection/issues).