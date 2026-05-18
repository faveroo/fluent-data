<?php

use Gabriel\FluentData\Pipeline\Pipeline;
use Gabriel\FluentData\Pipeline\Pipes\TrimName;
use Gabriel\FluentData\Pipeline\Pipes\UpperName;
use PHPUnit\Framework\TestCase;

class PipelineTest extends TestCase
{
    public function test_pipeline_applies_pipes_in_order(): void
    {
        $result = Pipeline::make([
            'name' => '  favero     ',
        ])->through([
            TrimName::class,
            UpperName::class
        ])->then(
            fn (array $payload) => $payload
        );

        $this->assertSame(
            ['name' => 'FAVERO'],
            $result
        );
    }

    public function test_pipeline_returns_destination_when_no_pipes_exist(): void
    {
        $result = Pipeline::make([
            'name' => 'Bruno',
        ])->through([])->then(
            fn (array $payload) => $payload['name']
        );

        $this->assertSame('Bruno', $result);
    }

    public function test_pipeline_can_pass_through_a_pipe_without_changes(): void
    {
        $payload = [
            'name' => 'Bruno',
        ];

        $result = Pipeline::make($payload)
            ->through([
                PassThroughPipe::class,
            ])->then(
                fn (array $passable) => $passable
            );

        $this->assertSame($payload, $result);
    }

    public function test_pipeline_does_not_mutate_the_original_payload_array(): void
    {
        $payload = [
            'name' => '  favero     ',
        ];

        Pipeline::make($payload)
            ->through([
                TrimName::class,
                UpperName::class,
            ])->then(
                fn (array $passable) => $passable
            );

        $this->assertSame(
            ['name' => '  favero     '],
            $payload
        );
    }

    public function test_pipeline_propagates_exceptions_from_pipes(): void
    {
        $this->expectException(
            RuntimeException::class
        );

        $this->expectExceptionMessage(
            'Pipe failed.'
        );

        Pipeline::make([
            'name' => 'Bruno',
        ])->through([
            ExplodingPipe::class,
        ])->then(
            fn (array $payload) => $payload
        );
    }
}

class PassThroughPipe
{
    public function handle(array $payload, callable $next): mixed
    {
        return $next($payload);
    }
}

class ExplodingPipe
{
    public function handle(array $payload, callable $next): mixed
    {
        throw new RuntimeException('Pipe failed.');
    }
}
