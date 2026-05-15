<?php

use Gabriel\FluentData\DTO\Attributes\ArrayString;
use Gabriel\FluentData\DTO\Attributes\Email;
use Gabriel\FluentData\DTO\Attributes\IntType;
use Gabriel\FluentData\DTO\Attributes\Max;
use Gabriel\FluentData\DTO\Attributes\Min;
use Gabriel\FluentData\DTO\Attributes\StringType;
use Gabriel\FluentData\Validation\ValidationException;
use Gabriel\FluentData\DTO\Attributes\Required;
use Gabriel\FluentData\DTO\Data\Data;
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
                'O campo name é obrigatório.',
                $errors['name'][0]
            );

            $this->assertEquals(
                'O campo email deve ser um e-mail válido',
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
            'name' => 'ab'
        ]);
    }

    public function test_it_validates_max_length(): void
    {
        $this->expectException(
            ValidationException::class
        );

        UserData::fromArray([
            'name' => 'Teste123456',
            'email' => 'email@email.com'
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
            'uuid' => 1231231231231
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
            'age' => "daikdawdaw"
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
            'uuid' => "1231231231231",
            'age' => 12,
            'config' => [
                'teste' => 1231
            ]
        ]);

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

    #[ArrayString]
    protected array $config;
}
