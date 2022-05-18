<?php

namespace App\Services;

use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeDriverService;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy as By;
use Facebook\WebDriver\WebDriverExpectedCondition as EC;
use Facebook\WebDriver\WebDriverWait;

class WhatsappWrapper
{
    protected RemoteWebDriver $driver;

    public function start() : void
    {
        $this->driver = RemoteWebDriver::create("http://localhost:4444", DesiredCapabilities::chrome());
        $this->driver->get("https://web.whatsapp.com/");
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function get_qr_login(): string
    {
        $waiter = new WebDriverWait($this->driver, 60);
        $canvas_qr = $waiter->until(EC::presenceOfElementLocated(By::cssSelector("canvas[aria-label='Scan me!']")));
        return $this->driver->executeScript("return arguments[0].toDataURL('image/png').substring(21);", [$canvas_qr]);
    }

    public function isLogged(): bool
    {
        try {
            $waiter = new WebDriverWait($this->driver, 10);
            $waiter->until(EC::presenceOfElementLocated(By::cssSelector("div.YtmXM")));
            return true;
        }
        catch (NoSuchElementException|TimeoutException|\Exception) {}
        return false;
    }

    public function __destruct()
    {
        $this->driver->quit();
    }

}
