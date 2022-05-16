<?php

namespace Tests\Feature;

use App\Services\WhatsappWrapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestWrapper extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $wrapper = new WhatsappWrapper();
        $wrapper->start();
        $qr_decoded = $wrapper->get_qr_login();
        

        $this->assertTrue(true);
    }
}
