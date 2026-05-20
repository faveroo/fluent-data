<?php

use Gabriel\FluentData\Facades\Str;
use PHPUnit\Framework\TestCase;

class FacadeTest extends TestCase
{
    public function test_str_slug_function(): void
    {
        $result = Str::slug('My slug test');

        $this->assertSame(
            'my-slug-test',
            $result
        );
    }

    public function test_str_studly_function(): void
    {
        $result = Str::studly('my_studly-test');

        $this->assertSame(
            'MyStudlyTest',
            $result
        );
    }

    public function test_str_camel_function(): void
    {
        $result = Str::camel('My-camel Case_test');

        $this->assertSame(
            'myCamelCaseTest',
            $result
        );
    }

    public function test_str_snake_function(): void
    {
        $result = Str::snake('This is my snake  Function');

        $this->assertSame(
            'this_is_my_snake_function',
            $result
        );
    }

    public function test_str_kebab_function(): void
    {
        $result = Str::kebab('This is_my KebabFunction');

        $this->assertSame(
            'this-is-my-kebab-function',
            $result
        );
    }

    public function test_str_startswith_function(): void
    {
        $result = Str::startsWith('Hello World!', 'Hello'); // Diferencia maiúsculas de minúsculas

        $this->assertSame(
            true,
            $result
        );
    }

    public function test_str_endswith_function(): void
    {
        $result = Str::endsWith('Hello World!', 'World');

        $this->assertSame(
            false,
            $result
        );
    }

    public function test_str_contains_function(): void
    {
        $result = Str::contains('The quick brown fox jumps over the lazy dog', 'fox');

        $this->assertSame(
            true,
            $result
        );
    }

    public function test_str_random_function_returns_string_with_requested_length(): void
    {
        $result = Str::random(24);

        $this->assertSame(24, strlen($result));
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]+$/', $result);
    }

    public function test_str_ascii_function(): void
    {
        $result = Str::ascii('teste');

        $this->assertSame(
            [116, 101, 115, 116, 101],
            $result
        );
    }

    public function test_str_randomize_function_keeps_the_same_characters(): void
    {
        $value = 'abcdef';
        $result = Str::randomize($value);

        $expectedChars = str_split($value);
        $resultChars = str_split($result);

        sort($expectedChars);
        sort($resultChars);

        $this->assertSame(strlen($value), strlen($result));
        $this->assertSame($expectedChars, $resultChars);
    }

    public function test_str_binary_function(): void
    {
        $result = Str::binary('teste');

        $this->assertSame(
            "01110100 01100101 01110011 01110100 01100101",
            $result
        );
    }

    public function test_str_limit_function(): void
    {
        $result = Str::limit('Hello World', 5);

        $this->assertSame(
            'Hello',
            $result
        );
    }

    public function test_str_before_function_returns_text_before_search(): void
    {
        $result = Str::before('user@example.com', '@');

        $this->assertSame(
            'user',
            $result
        );
    }

    public function test_str_before_function_returns_original_string_when_search_is_missing(): void
    {
        $result = Str::before('user@example.com', '#');

        $this->assertSame(
            'user@example.com',
            $result
        );
    }

    public function test_str_after_function_returns_text_after_search(): void
    {
        $result = Str::after('user@example.com', '@');

        $this->assertSame(
            'example.com',
            $result
        );
    }

    public function test_str_after_function_returns_original_string_when_search_is_missing(): void
    {
        $result = Str::after('user@example.com', '#');

        $this->assertSame(
            'user@example.com',
            $result
        );
    }

    public function test_str_between_function_returns_text_between_delimiters(): void
    {
        $result = Str::between('Hello [World]!', '[', ']');

        $this->assertSame(
            'World',
            $result
        );
    }

    public function test_str_between_function_returns_empty_string_when_start_is_missing(): void
    {
        $result = Str::between('Hello World]!', '[', ']');

        $this->assertSame('', $result);
    }

    public function test_str_between_function_returns_empty_string_when_end_is_missing(): void
    {
        $result = Str::between('Hello [World!', '[', ']');

        $this->assertSame('', $result);
    }

    public function test_str_has_function_is_case_sensitive_by_default(): void
    {
        $this->assertTrue(
            Str::has('Hello World', 'World')
        );

        $this->assertFalse(
            Str::has('Hello World', 'world')
        );
    }

    public function test_str_has_function_can_ignore_case_with_flag(): void
    {
        $this->assertTrue(
            Str::has('Hello World', 'world', 1)
        );
    }

    public function test_str_helpers_support_empty_strings(): void
    {
        $this->assertSame('', Str::slug(''));
        $this->assertSame('', Str::snake(''));
        $this->assertSame([], Str::ascii(''));
        $this->assertSame('', Str::binary(''));
    }

    public function test_str_slug_normalizes_multiple_separators(): void
    {
        $result = Str::slug('Hello---world___test');

        $this->assertSame(
            'hello-world-test',
            $result
        );
    }

    public function test_str_snake_normalizes_multiple_separators_and_case(): void
    {
        $result = Str::snake('  MyHTTP-Class__Name  ');

        $this->assertSame(
            'my_h_t_t_p_class_name',
            $result
        );
    }

    public function test_str_helpers_are_case_sensitive(): void
    {
        $this->assertFalse(
            Str::startsWith('Hello World!', 'hello')
        );

        $this->assertFalse(
            Str::endsWith('Hello World!', 'world!')
        );

        $this->assertFalse(
            Str::contains('The quick brown fox', 'FOX')
        );
    }
}
