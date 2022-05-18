<?php

namespace App\Http\Controllers;

use App\Models\WPPSession;
use App\Services\WhatsappWrapper;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Illuminate\Database\Eloquent\Collection;
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
        $data = $wrapper->start();
        $wrapper->goTo("https://web.whatsapp.com/");
        $qrImage = $wrapper->getQrLogin();
        $data["image"] = $qrImage;
        return $data;
    }

    /**
     * @throws Exception
     */
    public function isLogged(Request $request): bool
    {
        $sessionId = $request->get("sessionId");
        $foldername = $request->get("foldername");
        $wrapper = New WhatsappWrapper($sessionId, $foldername);
        $wrapper->start();
        if ($wrapper->isLogged())
        {
            $wppSession = new WPPSession;
            $wppSession->foldername = $foldername;
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
        $wppSession = WPPSession::first("id", $id);
        Storage::deleteDirectory("sessions\\" . $wppSession->foldername);
        $wppSession->delete();
    }

}
