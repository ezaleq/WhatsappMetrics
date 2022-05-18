<?php

namespace App\Http\Controllers;

use App\Services\AccountsService;
use App\Services\WhatsappWrapper;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\ArrayShape;


class AccountsController extends Controller
{
    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     * @throws Exception
     */
    #[ArrayShape(["image" => "string", "sessionId" => "string"])] public function getQr(Request $request): array
    {
        $wrapper = new WhatsappWrapper();
        $sessionId = $wrapper->start();
        $wrapper->go_to("https://web.whatsapp.com/");
        $qrImage = $wrapper->get_qr_login();
        return array(
            "image" => $qrImage,
            "sessionId" => $sessionId);
    }

    /**
     * @throws Exception
     */
    public function isLogged(Request $request): bool
    {
        $sessionId = $request->get("sessionId");
        echo $sessionId;
        $wrapper = New WhatsappWrapper($sessionId);
        $wrapper->start();
        if ($wrapper->isLogged())
        {
            $sessionData = $wrapper->getSession();

            $wrapper->quit();
            return true;
        }
        throw new Exception();
    }

}
