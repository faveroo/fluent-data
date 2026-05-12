<?php

use Gabriel\FluentData\DTO\Data\Data;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    public function test_data_hides_masked_fields_defined_in_subclass(): void
    {
        $data = UserData::fromArray([
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'secret',
        ]);

        $this->assertSame(
            [
                'name' => 'Ana',
                'email' => 'ana@example.com',
            ],
            $data->toArray()
        );
    }

    public function test_data_debug_info_respects_masked_and_only_rules(): void
    {
        $data = UserData::fromArray([
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'secret',
        ])->only(['name', 'password']);

        $this->assertSame(
            [
                'name' => 'Ana',
            ],
            $data->toArray()
        );
    }
}

class UserData extends Data
{
    protected array $masked = ['password'];

    protected string $name;

    protected string $email;

    protected string $password;
}
