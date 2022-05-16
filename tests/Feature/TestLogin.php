<?php

namespace Tests\Feature;

use App\Services\WhatsappWrapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestLogin extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_open(): void
    {
        $wrapper = new WhatsappWrapper();
        $wrapper->start();
        $this->assertTrue(true);
    }
}
