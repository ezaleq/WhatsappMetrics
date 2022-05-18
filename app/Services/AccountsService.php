<?php

namespace App\Services;

use App\Services\WhatsappWrapper;
use Carbon\Laravel\ServiceProvider;
use Exception;

class AccountsService extends ServiceProvider
{
    protected WhatsappWrapper|null $wrapper = null;
    public function boot()
    {
        $this->wrapper = new WhatsappWrapper();
    }

    public function

}
