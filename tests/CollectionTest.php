<?php

use Gabriel\FluentData\Collections\Collection;
use Gabriel\FluentData\Contracts\Arrayable;
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

    public function test_collection_filter_function(): void
    {
        $result = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])
                    ->filter(fn($item) => $item % 2 == 0)->all();
                    
        $this->assertSame(
            [2, 4, 6, 8, 10],
            $result
        );
        
    }

    public function test_collection_to_array_serializes_mixed_values(): void
    {
        $collection = Collection::make([
            new ArrayableValue(['name' => 'Ana']),
            new JsonValue(['role' => 'admin']),
            [
                'meta' => [
                    new JsonValue(['active' => true]),
                ],
            ],
        ]);

        $this->assertSame(
            [
                ['name' => 'Ana'],
                ['role' => 'admin'],
                [
                    'meta' => [
                        ['active' => true],
                    ],
                ],
            ],
            $collection->toArray()
        );
    }

    public function test_collection_to_array_supports_json_serializable_scalars(): void
    {
        $collection = Collection::make([
            new JsonValue('ok'),
            new JsonValue(10),
        ]);

        $this->assertSame(
            ['ok', 10],
            $collection->toArray()
        );
    }

    public function test_collection_first_and_last_return_null_for_empty_collection(): void
    {
        $collection = collect([]);

        $this->assertNull($collection->first());
        $this->assertNull($collection->last());
    }

    public function test_collection_map_returns_empty_collection_when_items_are_empty(): void
    {
        $result = collect([])
            ->map(fn ($item) => $item);

        $this->assertSame([], $result->all());
    }

    public function test_collection_filter_returns_empty_collection_when_no_items_match(): void
    {
        $result = collect([1, 3, 5])
            ->filter(fn (int $item) => $item % 2 === 0);

        $this->assertSame([], $result->all());
    }

    public function test_collection_pluck_returns_null_for_missing_key(): void
    {
        $result = Collection::make($this->items)
            ->pluck('stock');

        $this->assertSame([null, null], $result->all());
    }

    public function test_collection_contains_uses_strict_comparison(): void
    {
        $collection = collect([1, 2, 3]);

        $this->assertFalse($collection->contains('1'));
        $this->assertTrue($collection->contains(1));
    }

    public function test_collection_where_filters_items_by_key_and_value(): void
    {
        $result = Collection::make($this->items)
            ->where('price', 20)
            ->all();

        $this->assertSame(
            [
                ['name' => 'Manga', 'price' => 20],
            ],
            array_values($result)
        );
    }

    public function test_collection_where_supports_comparison_operators(): void
    {
        $result = Collection::make($this->items)
            ->where('price', 10, '>')
            ->all();

        $this->assertSame(
            [
                ['name' => 'Manga', 'price' => 20],
            ],
            array_values($result)
        );
    }

    public function test_collection_where_returns_empty_collection_for_unknown_operator(): void
    {
        $result = Collection::make($this->items)
            ->where('price', 10, '<>')
            ->all();

        $this->assertSame([], $result);
    }

    public function test_collection_first_where_returns_first_matching_item(): void
    {
        $result = Collection::make([
            ['name' => 'Banana', 'price' => 5],
            ['name' => 'Manga', 'price' => 20],
            ['name' => 'Melancia', 'price' => 20],
        ])->firstWhere('price', 20);

        $this->assertSame(
            ['name' => 'Manga', 'price' => 20],
            $result
        );
    }

    public function test_collection_first_where_returns_null_when_nothing_matches(): void
    {
        $result = Collection::make($this->items)
            ->firstWhere('price', 100);

        $this->assertNull($result);
    }

    public function test_collection_sum_returns_total_of_numeric_values(): void
    {
        $result = Collection::make($this->items)
            ->sum('price');

        $this->assertSame(30, $result);
    }

    public function test_collection_sum_returns_zero_for_empty_collection(): void
    {
        $result = collect([])
            ->sum();

        $this->assertSame(0, $result);
    }

    public function test_collection_avg_returns_average_of_numeric_values(): void
    {
        $result = Collection::make($this->items)
            ->avg('price');

        $this->assertSame(15.0, $result);
    }

    public function test_collection_avg_returns_null_for_empty_collection(): void
    {
        $result = collect([])
            ->avg();

        $this->assertNull($result);
    }

}

class ArrayableValue implements Arrayable
{
    public function __construct(
        private array $data
    ) {}

    public function toArray(): array
    {
        return $this->data;
    }
}

class JsonValue implements \JsonSerializable
{
    public function __construct(
        private mixed $data
    ) {}

    public function jsonSerialize(): mixed
    {
        return $this->data;
    }
}
