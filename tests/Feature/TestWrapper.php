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

    public function create() : string
    {
        $wrapper = new WhatsappWrapper();
        $session_id = $wrapper->start();
        $wrapper->go_to("https://web.whatsapp.com/");
        return $session_id;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
//    public function test_sessions(): void
//    {
//        $session_id = $this->create();
//        $wrapper = new WhatsappWrapper($session_id);
//        $wrapper->start();
//        $data = $wrapper->get_qr_login();
//        $this->assertTrue(isset($data));
//    }

    public function test_getting_session(): void
    {
        $wrapper = new WhatsappWrapper();
        $wrapper->start();
        $wrapper->go_to("https://web.whatsapp.com/");
        while(!$wrapper->isLogged())
        {
            continue;
        }
        $session = $wrapper->getSession();
        self::assertNotEmpty($session);
    }
}
