<?php

namespace App\Services;


use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Facades\Log;

class WhatsappWrapper
{
    public function start(): void
    {
        $driver = RemoteWebDriver::create(desired_capabilities: DesiredCapabilities::chrome());
        $driver->get("https://web.whatsapp.com/");
    }
}
