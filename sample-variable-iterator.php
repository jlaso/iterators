<?php

include __DIR__ . '/vendor/autoload.php';

use JLaso\Iterators\VariableIterator;

$iterator = new VariableIterator([
    'genre' => ['W', 'M', '?'],
    'ages' => ['18-25', '26-58', '+59'],
    'profession' => ['engineer', 'services', 'others'],
    'holidays' => ['none', '1-10', '+10'],
]);

foreach ($iterator as $item) {

    print join(',', $item) . PHP_EOL;

}