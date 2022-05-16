<?php

namespace App\Services;

use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeDriverService;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\WebDriverBy as By;
use Facebook\WebDriver\WebDriverExpectedCondition as EC;
use Facebook\WebDriver\WebDriverWait;

class WhatsappWrapper
{
    protected ChromeDriver $driver;

    public function start() : void
    {
        putenv(ChromeDriverService::CHROME_DRIVER_EXECUTABLE . '=' . getenv("CHROMEDRIVER_PATH"));
        $this->driver = ChromeDriver::start();
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
        $canvas_base64 = $this->driver->executeScript("return arguments[0].toDataURL('image/png').substring(21);", [$canvas_qr]);
        return base64_decode($canvas_base64);
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function wait_until_logged(): void
    {
        $waiter = new WebDriverWait($this->driver, 60 * 5);
        $waiter->until(EC::presenceOfElementLocated(By::cssSelector("div.YtmXM")));
    }
}
