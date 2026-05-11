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
}
