# iterators

## installation 

```
composer require jlaso/itearators
```

* * *

## variable-iterator

simplifies the use of a complex array to iterate over all the possibilities


### how to use

```php
use JLaso\Iterators\VariableIterator;

$iterator = new VariableIterator([
    'genre' => ['W', 'M', '?'],
    'ages' => ['18-25', '26-58', '+59'],
    'profession' => ['engineer', 'services', 'others'],
    'holidays' => ['none', '1-10', '+10'],
]);

foreach ($iterator as $item) {
    // you have in every $item the current mix of variations
}
```

* * *

## unit testing

```
vendor/bin/phpunit
```
