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

    public function test_arr_get_returns_nested_value_from_dot_path(): void
    {
        $result = $this->arr->get(
            [
                'user' => [
                    'profile' => [
                        'name' => 'Ana',
                    ],
                ],
            ],
            'user.profile.name'
        );

        $this->assertSame('Ana', $result);
    }

    public function test_arr_get_returns_all_items_when_key_is_null(): void
    {
        $items = [
            'user' => [
                'name' => 'Ana',
            ],
        ];

        $result = $this->arr->get($items, null);

        $this->assertSame($items, $result);
    }

    public function test_arr_get_returns_null_for_missing_path(): void
    {
        $result = $this->arr->get(
            [
                'user' => [
                    'profile' => [],
                ],
            ],
            'user.profile.email'
        );

        $this->assertSame(null, $result);
    }

    public function test_arr_has_returns_true_for_existing_dot_path(): void
    {
        $result = $this->arr->has(
            [
                'user' => [
                    'profile' => [
                        'name' => 'Ana',
                    ],
                ],
            ],
            'user.profile.name'
        );

        $this->assertSame(true, $result);
    }

    public function test_arr_has_returns_false_for_missing_dot_path(): void
    {
        $result = $this->arr->has(
            [
                'user' => [
                    'profile' => [],
                ],
            ],
            'user.profile.email'
        );

        $this->assertSame(false, $result);
    }

    public function test_arr_set_creates_nested_path_and_assigns_value(): void
    {
        $result = $this->arr->set([], 'user.profile.name', 'Ana');

        $this->assertSame(
            [
                'user' => [
                    'profile' => [
                        'name' => 'Ana',
                    ],
                ],
            ],
            $result
        );
    }

    public function test_arr_set_overwrites_existing_nested_value(): void
    {
        $result = $this->arr->set(
            [
                'user' => [
                    'profile' => [
                        'name' => 'Bruno',
                    ],
                ],
            ],
            'user.profile.name',
            'Ana'
        );

        $this->assertSame(
            [
                'user' => [
                    'profile' => [
                        'name' => 'Ana',
                    ],
                ],
            ],
            $result
        );
    }

    public function test_arr_forget_removes_existing_nested_key(): void
    {
        $result = $this->arr->forget(
            [
                'user' => [
                    'profile' => [
                        'name' => 'Ana',
                        'email' => 'ana@email.com',
                    ],
                ],
            ],
            'user.profile.email'
        );

        $this->assertSame(
            [
                'user' => [
                    'profile' => [
                        'name' => 'Ana',
                    ],
                ],
            ],
            $result
        );
    }

    public function test_arr_forget_returns_original_array_when_path_does_not_exist(): void
    {
        $items = [
            'user' => [
                'profile' => [
                    'name' => 'Ana',
                ],
            ],
        ];

        $result = $this->arr->forget($items, 'user.profile.phone');

        $this->assertSame($items, $result);
    }

    public function test_arr_except_removes_multiple_keys(): void
    {
        $result = $this->arr->except(
            [
                'name' => 'Ana',
                'email' => 'ana@email.com',
                'age' => 20,
            ],
            ['email', 'age']
        );

        $this->assertSame(
            [
                'name' => 'Ana',
            ],
            $result
        );
    }

    public function test_arr_except_supports_nested_keys(): void
    {
        $result = $this->arr->except(
            [
                'user' => [
                    'name' => 'Ana',
                    'email' => 'ana@email.com',
                ],
                'meta' => [
                    'active' => true,
                ],
            ],
            ['user.email', 'meta.active']
        );

        $this->assertSame(
            [
                'user' => [
                    'name' => 'Ana',
                ],
                'meta' => [],
            ],
            $result
        );
    }

    public function test_arr_dot_flattens_nested_array(): void
    {
        $result = $this->arr->dot(
            [
                'user' => [
                    'profile' => [
                        'name' => 'Ana',
                    ],
                ],
                'active' => true,
            ]
        );

        $this->assertSame(
            [
                'user.profile.name' => 'Ana',
                'active' => true,
            ],
            $result
        );
    }

    public function test_arr_dot_keeps_empty_array_as_value(): void
    {
        $result = $this->arr->dot(
            [
                'user' => [],
            ]
        );

        $this->assertSame(
            [
                'user' => [],
            ],
            $result
        );
    }

    public function test_arr_undot_rebuilds_nested_array_from_dot_notation(): void
    {
        $result = $this->arr->undot(
            [
                'user.profile.name' => 'Ana',
                'user.profile.email' => 'ana@email.com',
            ]
        );

        $this->assertSame(
            [
                'user' => [
                    'profile' => [
                        'name' => 'Ana',
                        'email' => 'ana@email.com',
                    ],
                ],
            ],
            $result
        );
    }

    public function test_arr_flatten_flattens_nested_arrays_into_single_list(): void
    {
        $result = $this->arr->flatten([1, [2, [3, 4]], 5]);

        $this->assertSame([1, 2, 3, 4, 5], $result);
    }

    public function test_arr_flatten_returns_empty_array_when_input_is_empty(): void
    {
        $result = $this->arr->flatten([]);

        $this->assertSame([], $result);
    }
}
