<?php

namespace Tests\Feature;

use App\Models\WPPSession;
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

    public function create() : array
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
//        $data = $this->create();
//        $wrapper = new WhatsappWrapper($data["sessionId"], $data["foldername"]);
//        $wrapper->start();
//        $data = $wrapper->get_qr_login();
//        $this->assertTrue(isset($data));
//    }

//    public function test_getting_session(): void
//    {
//        $wrapper = new WhatsappWrapper(folder: "Ezequiel Q");
//        $wrapper->start();
//        $wrapper->go_to("https://web.whatsapp.com/");
//        while(!$wrapper->isLogged())
//        {
//            continue;
//        }
//        $username = $wrapper->getUsername();
//        $wrapper->quit();
//        self::assertEquals("Ezequiel Q", $username);
//    }

    public function test_loading_session()
    {

        $foldername = WPPSession::first()->foldername;
        $wrapper = new WhatsappWrapper(folder: $foldername);
        $wrapper->start();
        $wrapper->go_to("https://web.whatsapp.com");
        self::assertTrue($wrapper->isLogged());
    }
}
