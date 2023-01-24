<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ArrayHasKeyTest extends TestCase
{
    public function testFailure(): void
    {
        $this->assertArrayHasKey('bar', ['bar' => 'baz']);
    }
}
