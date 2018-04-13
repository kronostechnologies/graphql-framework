<?php

namespace Kronos\Tests;


use PHPUnit\Framework\TestCase;

class SampleTest extends TestCase
{
    public function test_Pass()
    {
        $this->assertTrue(true);
    }

    public function test_Risky()
    {

    }

    public function test_NotPass()
    {
        $this->assertFalse(true);
    }
}