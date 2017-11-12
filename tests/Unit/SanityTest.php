<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SanityTest extends TestCase
{
    /**
     * A basic test to see if boolean logic still works as expected.
     * 
     * @test
     * @return void
     */
    public function sanityTest()
    {
        $this->assertTrue(true);
    }
}
