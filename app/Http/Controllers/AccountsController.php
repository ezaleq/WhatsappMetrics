<?php

namespace App\Http\Controllers;

use App\Services\AccountsService;
use App\Services\WhatsappWrapper;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;


class AccountsController extends Controller
{
    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     * @throws Exception
     */
    public function getQr(Request $request): string
    {
        $wrapper = new WhatsappWrapper();
        $wrapper->start();
        $qrImage = $wrapper->get_qr_login();
        $request->session()->put("wrapper", $wrapper);
        return $qrImage;
    }

    /**
     * @throws Exception
     */
    public function isLogged(Request $request): bool
    {
        $wrapper = $request->session()->get("wrapper");
        if ($wrapper->isLogged())
        {
            return true;
        }
        throw new Exception();
    }

}
