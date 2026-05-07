<?php

use Gabriel\FluentData\Collections\Collection;
use Gabriel\FluentData\DTO\Attributes\Required;
use Gabriel\FluentData\DTO\Data;
use Gabriel\FluentData\Pipeline\Pipeline;
use Gabriel\FluentData\Pipes\TrimStrings;
use Gabriel\FluentData\Pipes\UpperStrings;

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

echo $total . "\n";

class UserData extends Data
{
    #[Required]
    public string $name;
    
    public string $email;
}

try {
    $user = UserData::fromArray([
        'email' => 'teste@teste.com',
        'name' => 'Gabriel'
    ]);
} catch ( Exception $e ) {
    echo $e->getMessage();
}

echo "\n";

$result = Pipeline::make([
    'name' => 'Gabriel'
])->through([
    TrimStrings::class,
    UpperStrings::class,
])->then(fn ($data) => $data);

print_r($result);