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
use Facebook\WebDriver\WebDriverExpectedCondition as EC;
use Facebook\WebDriver\WebDriverWait;

class WhatsappWrapper
{
    protected RemoteWebDriver $driver;
    protected ?string $sessionId = null;

    public function __construct($sessionId = null)
    {
        $this->sessionId = $sessionId;
    }

    public function start(): string
    {
        if (empty($this->sessionId)) {
            $this->driver = RemoteWebDriver::create("http://localhost:4444", DesiredCapabilities::chrome());
        } else {
            error_log("is set");
            $this->driver = RemoteWebDriver::createBySessionID($this->sessionId, "http://localhost:4444");
        }
        return $this->driver->getSessionID();
    }

    public function go_to($url): void
    {
        $this->driver->get($url);
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
            $waiter = new WebDriverWait($this->driver, 15);
            $waiter->until(EC::presenceOfElementLocated(By::cssSelector("div.YtmXM")));
            return true;
        } catch (NoSuchElementException|TimeoutException|\Exception) {
        }
        return false;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function getUserPhone() : string
    {
        $waiter = new WebDriverWait($this->driver, 5);
        /** @var $element RemoteWebElement */
        $element =  $waiter->until(EC::presenceOfElementLocated(By::cssSelector("div._3GlyB")));
        $element->click();
    }

    public function getSession(): string
    {
        return $this->driver->executeScript("
            function getResultFromRequest(request) {
                return new Promise((resolve, reject) => {
                    request.onsuccess = function (event) {
                        resolve(request.result);
                    };
                });
            }

            async function getDB() {
                var request = window.indexedDB.open('wawc');
                return await getResultFromRequest(request);
            }

            async function readAllKeyValuePairs() {
                var db = await getDB();
                var objectStore = db.transaction('user').objectStore('user');
                var request = objectStore.getAll();
                   return await getResultFromRequest(request);
            }

            var session = await readAllKeyValuePairs();
            return JSON.stringify(session);
        ");
    }

    public function quit()
    {
        $this->driver->quit();
    }

}
