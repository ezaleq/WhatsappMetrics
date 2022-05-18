<?php

namespace Tests\Feature;

use App\Services\WhatsappWrapper;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
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
    public function test_example(): void
    {
        $wrapper = new WhatsappWrapper();
        $wrapper->start();
        try {
            $qr_decoded = $wrapper->get_qr_login();
            $wrapper->isLogged();
        } catch (NoSuchElementException|TimeoutException $e) {
            $this->fail();
        }
        $this->assertTrue(true);
    }
}
