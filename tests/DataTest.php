<?php

use Gabriel\FluentData\DTO\Attributes\ArrayType;
use Gabriel\FluentData\DTO\Attributes\Email;
use Gabriel\FluentData\DTO\Attributes\IntType;
use Gabriel\FluentData\DTO\Attributes\Max;
use Gabriel\FluentData\DTO\Attributes\Min;
use Gabriel\FluentData\DTO\Attributes\Required;
use Gabriel\FluentData\DTO\Attributes\StringType;
use Gabriel\FluentData\DTO\Data\Data;
use Gabriel\FluentData\Validation\ValidationException;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    public function test_data_hides_masked_fields_defined_in_subclass(): void
    {
        $data = UserData::fromArray([
            'name' => 'Nome',
            'email' => 'Nome@example.com',
            'password' => 'secret',
        ]);

        $this->assertSame(
            [
                'name' => 'Nome',
                'email' => 'Nome@example.com',
            ],
            $data->toArray()
        );
    }

    public function test_data_debug_info_respects_masked_and_only_rules(): void
    {
        $data = UserData::fromArray([
            'name' => 'favero',
            'email' => 'favero@example.com',
            'password' => 'secret',
        ])->only(['name', 'password']);

        $this->assertSame(
            [
                'name' => 'favero',
            ],
            $data->toArray()
        );
    }

    public function test_it_returns_validation_errors(): void
    {
        try {
            UserData::fromArray([
                'name' => '',
                'email' => 'invalid-email',
            ]);

            $this->fail(
                'ValidationException was not thrown.'
            );
        } catch (ValidationException $e) {
            $errors = $e->errors();

            $this->assertArrayHasKey(
                'name',
                $errors
            );

            $this->assertArrayHasKey(
                'email',
                $errors
            );

            $this->assertEquals(
                'The name field is required.',
                $errors['name'][0]
            );

            $this->assertEquals(
                'The email field must be a valid email address.',
                $errors['email'][0]
            );
        }
    }

    public function test_it_validates_min_length(): void
    {
        $this->expectException(
            ValidationException::class
        );

        UserData::fromArray([
            'name' => 'ab',
        ]);
    }

    public function test_it_validates_max_length(): void
    {
        $this->expectException(
            ValidationException::class
        );

        UserData::fromArray([
            'name' => 'Teste123456',
            'email' => 'email@email.com',
        ]);
    }

    public function test_it_validates_stringType(): void
    {
        $this->expectException(
            ValidationException::class
        );

        UserData::fromArray([
            'name' => 'Name',
            'email' => 'email@email.com',
            'uuid' => 1231231231231,
        ]);
    }

    public function test_it_validates_intType(): void
    {
        $this->expectException(
            ValidationException::class
        );

        UserData::fromArray([
            'name' => 'Name',
            'email' => 'email@email.com',
            'uuid' => 1231231231231,
            'age' => 'daikdawdaw',
        ]);
    }

    public function test_it_validates_ArrayString(): void
    {
        $this->expectException(
            ValidationException::class
        );

        UserData::fromArray([
            'name' => 'Name',
            'email' => 'email@email.com',
            'uuid' => '1231231231231',
            'age' => 12,
            'config' => [
                'teste' => '1231',
            ],
        ]);
    }

    public function test_data_from_array_ignores_missing_non_required_fields(): void
    {
        $data = UserData::fromArray([
            'name' => 'Name',
            'email' => 'email@email.com',
        ]);

        $this->assertSame(
            [
                'name' => 'Name',
                'email' => 'email@email.com',
            ],
            $data->toArray()
        );
    }

    public function test_data_allows_null_for_non_required_fields(): void
    {
        $data = UserData::fromArray([
            'name' => 'Name',
            'email' => 'email@email.com',
            'age' => null,
            'uuid' => null,
        ]);

        $this->assertSame(
            [
                'name' => 'Name',
                'email' => 'email@email.com',
                'age' => null,
                'uuid' => null,
            ],
            $data->toArray()
        );
    }

    public function test_data_collects_multiple_validation_errors_for_the_same_field(): void
    {
        try {
            MultiRuleUserData::fromArray([
                'name' => '',
            ]);

            $this->fail(
                'ValidationException was not thrown.'
            );
        } catch (ValidationException $e) {
            $errors = $e->errors();

            $this->assertSame(
                [
                    'The name field is required.',
                    'The name field must be at least 3 characters.',
                ],
                $errors['name']
            );
        }
    }

    public function test_data_only_and_masked_can_be_combined(): void
    {
        $data = UserData::fromArray([
            'name' => 'Name',
            'email' => 'email@email.com',
            'password' => 'secret',
            'uuid' => 'abc123',
        ])->only(['name', 'email', 'password', 'uuid'])
            ->masked(['password', 'uuid']);

        $this->assertSame(
            [
                'name' => 'Name',
                'email' => 'email@email.com',
            ],
            $data->toArray()
        );
    }
}

class UserData extends Data
{
    protected array $masked = ['password'];

    #[Required]
    #[Min(3)]
    #[Max(6)]
    protected string $name;

    #[Required]
    #[Email]
    protected string $email;

    protected string $password;

    #[IntType]
    protected mixed $age;

    #[StringType]
    protected mixed $uuid;

    #[ArrayType('int')]
    protected array $config;
}

class MultiRuleUserData extends Data
{
    #[Required]
    #[Min(3)]
    protected string $name;
}
