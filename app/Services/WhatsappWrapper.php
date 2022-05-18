<?php

namespace App\Services;

use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeDriverService;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy as By;
use Facebook\WebDriver\WebDriverExpectedCondition as EC;
use Facebook\WebDriver\WebDriverWait;
use Illuminate\Support\Str;

class WhatsappWrapper
{
    protected RemoteWebDriver $driver;
    protected ?string $sessionId = null;
    protected string $folder;

    public function __construct($sessionId = null, $folder = null)
    {
        $this->sessionId = $sessionId;
        if (empty($folder))
        {
            $this->folder = Str::uuid()->toString();
        }
        else
        {
            $this->folder = $folder;
        }
    }

    public function start(): string
    {
        if (empty($this->sessionId)) {
            $capabilities = DesiredCapabilities::chrome();
            $options = new ChromeOptions();
            $storagePath = storage_path("app/sessions/Ezequiel Q/");

            $options->addArguments(["--user-data-dir=" . $storagePath]);
            $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
            $this->driver = RemoteWebDriver::create("http://localhost:4444", $capabilities );
        } else {
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

    /**
     * Returns boolean indicating if is logged (checks for 2 minutes)
     * @return bool Login status
     */
    public function isLogged(): bool
    {
        try {
            $waiter = new WebDriverWait($this->driver, 2 * 60);
            $waiter->until(EC::presenceOfElementLocated(By::cssSelector("div.YtmXM")));
            return true;
        } catch (NoSuchElementException|TimeoutException|\Exception) {
        }
        return false;
    }

    /**
     * Retreives the username from the account logged
     * @return string Username
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function getUsername() : string
    {
        $waiter = new WebDriverWait($this->driver, 5);
        /** @var $userPicture RemoteWebElement */
        $userPicture =  $waiter->until(EC::elementToBeClickable(By::cssSelector("div._3GlyB")));
        $userPicture->click();
        /** @var $userNameContainer RemoteWebElement */
        $userNameContainer = $waiter->until(EC::visibilityOfElementLocated(By::cssSelector("div[class='_1UWac Z2O8p'] > div[role='textbox']")));
        while (empty($userNameContainer->getText())) { }
        return $userNameContainer->getText();
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

    public function setSession($sessionString) : void
    {
        $this->driver->executeScript("
            function getResultFromRequest(request) {
                return new Promise((resolve, reject) => {
                    request.onsuccess = function(event) {
                        resolve(request.result);
                    };
                })
            }

            async function getDB() {
                var request = window.indexedDB.open('wawc');
                return await getResultFromRequest(request);
            }

            async function injectSession(SESSION_STRING) {
                var session = JSON.parse(SESSION_STRING);
                console.log(session);
                var db = await getDB();
                var objectStore = db.transaction('user', 'readwrite').objectStore('user');
                for(var keyValue of session) {
                    var request = objectStore.put(keyValue);
                    await getResultFromRequest(request);
                }
            }
            await injectSession(arguments[0]);
        ", [$sessionString]);
    }

    public function quit()
    {
        $this->driver->quit();
    }

}
