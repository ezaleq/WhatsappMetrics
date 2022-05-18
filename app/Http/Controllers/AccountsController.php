<?php

namespace App\Http\Controllers;

use App\Models\WPPSession;
use App\Services\WhatsappWrapper;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
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
            $wppSession = new WPPSession;
            $wppSession->session = $wrapper->getSession();
            $wppSession->username = $wrapper->getUsername();
            $wppSession->save();
            $wrapper->quit();
            return true;
        }
        throw new Exception();
    }

    public function getAccounts(): Collection|\Illuminate\Support\Collection
    {
        return WPPSession::all()->map(function($wppAccount) {
            return array(
                "username" => $wppAccount->username,
                "id" => $wppAccount->id);
        });
    }

    public function deleteAccount(Request $request)
    {
        $id = $request->get("id");
        WPPSession::where("id", $id)->delete();
    }

}
