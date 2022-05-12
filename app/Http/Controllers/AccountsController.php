<?php

namespace App\Http\Controllers;

use App\Services\WhatsappWrapper;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;


class AccountsController extends Controller
{
    public function index(): View
    {
        return View("accounts");
    }

    public function create(): bool
    {
        $wrapper = new WhatsappWrapper();
        $wrapper->start();
        return true;
    }
}
