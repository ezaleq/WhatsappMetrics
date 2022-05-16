<?php

namespace App\Services;


use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeDriverService;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy as By;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverExpectedCondition as EC;
use Facebook\WebDriver\WebDriverWait;
use Illuminate\Support\Facades\Log;

class WhatsappWrapper
{
    protected ChromeDriver $driver;

    public function start() : void
    {
        putenv(ChromeDriverService::CHROME_DRIVER_EXECUTABLE . '=' . getenv("CHROMEDRIVER_PATH"));
        $this->driver = ChromeDriver::start();
        $this->driver->get("https://web.whatsapp.com/");
    }

    public function get_qr_login(): string
    {
        $waiter = new WebDriverWait($this->driver, 60);
        try {
            /** @var RemoteWebElement  */
            $canvas_qr = $waiter->until(EC::presenceOfElementLocated(By::cssSelector("canvas[aria-label='Scan me!']")));
        } catch (NoSuchElementException|TimeoutException|\Exception $e) {
            return false;
        }
        $canvas_base64 = $this->driver->executeScript("return arguments[0].toDataURL('image/png').substring(21);", [$canvas_qr]);
        return base64_decode($canvas_base64);
    }
}
