<?php

use Gabriel\FluentData\Collections\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    protected array $items = [
        ['name' => 'Abacate', 'price' => 10],
        ['name' => 'Manga', 'price' => 20],
    ];

    public function test_collection_macros(): void
    {
        Collection::macro('sumPrices', function () {
            return array_sum(
                array_column($this->items, 'price')
            );
        });

        $result = Collection::make($this->items)->sumPrices();

        $this->assertSame(
            30,
            $result,
        );
    }

    public function test_collection_map_function(): void
    {
        $result = Collection::make($this->items)
            ->map(function ($item) {
                $item['price'] *= 2;
                return $item;
            });

        $this->assertSame(
            [
                ['name' => 'Abacate', 'price' => 20],
                ['name' => 'Manga', 'price' => 40],
            ],
            $result->all()
        );
    }

    public function test_collection_fist_and_last_function(): void
    {
        $collection = collect([1, 10, 20, 30, 40, 50, 66, 70]);
        $first = $collection->first();
        $last = $collection->last();

        $result = [$first, $last];

        $this->assertSame(
            [1, 70],
            $result
        );
    }

    public function test_collection_count_function(): void
    {
        $result = Collection::make($this->items)->count();

        $this->assertSame(
            2,
            $result
        );
    }

}
