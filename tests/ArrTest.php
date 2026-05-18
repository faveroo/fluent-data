<?php

use Gabriel\FluentData\Support\Arr;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
    protected Arr $arr;

    protected function setUp(): void
    {
        parent::setUp();

        $this->arr = new Arr();
    }

    public function test_arr_first_returns_default_for_empty_array(): void
    {
        $result = $this->arr->first([], default: 'fallback');

        $this->assertSame('fallback', $result);
    }

    public function test_arr_last_returns_default_for_empty_array(): void
    {
        $result = $this->arr->last([], default: 'fallback');

        $this->assertSame('fallback', $result);
    }

    public function test_arr_first_can_find_item_with_callback(): void
    {
        $result = $this->arr->first(
            [1, 3, 4, 8],
            fn (int $value) => $value % 2 === 0
        );

        $this->assertSame(4, $result);
    }

    public function test_arr_last_can_find_item_with_callback(): void
    {
        $result = $this->arr->last(
            [1, 3, 4, 8],
            fn (int $value) => $value % 2 === 0
        );

        $this->assertSame(8, $result);
    }

    public function test_arr_contains_uses_strict_comparison(): void
    {
        $this->assertFalse(
            $this->arr->contains([1, 2, 3], '1')
        );

        $this->assertTrue(
            $this->arr->contains([1, 2, 3], 1)
        );
    }

    public function test_arr_filter_preserves_matching_keys(): void
    {
        $result = $this->arr->filter(
            ['first' => 1, 'second' => 2, 'third' => 3],
            fn (int $value) => $value > 1
        );

        $this->assertSame(
            ['second' => 2, 'third' => 3],
            $result
        );
    }

    public function test_arr_pluck_returns_null_for_missing_keys(): void
    {
        $result = $this->arr->pluck(
            [
                ['name' => 'Ana'],
                ['email' => 'bruno@example.com'],
            ],
            'name'
        );

        $this->assertSame(
            ['Ana'],
            $result
        );
    }

    public function test_arr_only_returns_only_the_requested_keys(): void
    {
        $result = $this->arr->only(
            [
                'name' => 'Pedro',
                'email' => 'pedro@email',
                'age' => 20,
            ],
            ['name', 'email']
        );

        $this->assertSame(
            [
                'name' => 'Pedro',
                'email' => 'pedro@email',
            ],
            $result
        );
    }

    public function test_arr_only_ignores_missing_keys(): void
    {
        $result = $this->arr->only(
            [
                'name' => 'Pedro',
            ],
            ['name', 'email']
        );

        $this->assertSame(
            [
                'name' => 'Pedro',
            ],
            $result
        );
    }
}
