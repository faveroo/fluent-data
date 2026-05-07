<?php

use Gabriel\FluentData\Collections\Collection;

require 'vendor/autoload.php';

Collection::macro('sumPrices', function() {
    return array_sum(
        array_column($this->items, 'price')
    );
});

$total = collect([
    ['price' => 10],
    ['price' => 20],
])->sumPrices();

echo $total;