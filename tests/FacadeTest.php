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

    public function test_str_ascii_function(): void
    {
        $result = Str::ascii('teste');

        $this->assertSame(
            [116, 101, 115, 116, 101],
            $result
        );
    }

    public function test_str_binary_function(): void
    {
        $result = Str::binary('teste');

        $this->assertSame(
            "01110100 01100101 01110011 01110100 01100101",
            $result
        );
    }
}
