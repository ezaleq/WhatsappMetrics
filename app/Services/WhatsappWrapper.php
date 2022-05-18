<?php

namespace App\Services;

use Exception;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeDriverService;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy as By;
use Facebook\WebDriver\WebDriverExpectedCondition as EC;
use Facebook\WebDriver\WebDriverWait;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class WhatsappWrapper
{
    protected RemoteWebDriver $driver;
    protected ?string $sessionId = null;
    protected string $folder;

    public function __construct($sessionId = null, string $folder = null)
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

    #[ArrayShape(["sessionId" => "string", "foldername" => "string"])] public function start(): array
    {
        if (empty($this->sessionId)) {
            $capabilities = DesiredCapabilities::chrome();
            $options = new ChromeOptions();
            $storagePath = storage_path("sessions\\" . $this->folder);
            $options->addArguments(["--user-data-dir=" . $storagePath]);
            $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
            $this->driver = RemoteWebDriver::create("http://localhost:4444", $capabilities );
        } else {
            $this->driver = RemoteWebDriver::createBySessionID($this->sessionId, "http://localhost:4444");
        }
        return array(
            "sessionId" => $this->driver->getSessionID(),
            "foldername" => $this->folder
        );
    }

    public function goTo($url): void
    {
        $this->driver->get($url);
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function getQrLogin(): string
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

    public function getFoldername() : string
    {
        return $this->folder;
    }

    protected function getChatName(RemoteWebElement $chatElement) : string
    {
        $titleSpan = $chatElement->findElement(By::cssSelector(
            "div > div > div._3OvU8 > div._3vPI2 > div > span[dir='auto']"));
        return $titleSpan->getText();
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function getChatByName(string $chatName) : RemoteWebElement
    {
        /**
         * @var $chatElements RemoteWebElement[]
         * @var $searchBox RemoteWebElement
         */
        $waiter = new WebDriverWait($this->driver, 20);
        $searchBox = $waiter->until(EC::presenceOfElementLocated(By::cssSelector("div[role='textbox'][class*='_13NKt copyable-text selectable-text']")));
        $searchBox->sendKeys($chatName);
        $chatElements = $waiter->until(EC::presenceOfAllElementsLocatedBy(By::cssSelector("div._3m_Xw")));
        foreach ($chatElements as $chatElement)
        {
            try
            {
                $chatElement->findElement(By::cssSelector("span[title='" . $chatName . "']"));
                return $chatElement;
            }
            catch (Exception) { continue; }
        }
        throw new NoSuchElementException("No se encontraron chats con el nombre especificado");
    }

    public function getMessagesFromChat(RemoteWebElement $chatElement) : string
    {
        /** @var $messageElements RemoteWebElement[] */
        $chatElement->click();

        $chatView = $this->driver->findElement(By::cssSelector("div._2gzeB"));
        $waiter = new WebDriverWait($this->driver, 10);
        $messageElements = $waiter->until(EC::presenceOfAllElementsLocatedBy(By::cssSelector("div[class*='message-'][class='focusable-list-item']")));
        $firstMessage = $messageElements[0]->getText();
        return $firstMessage;
    }

    protected function parseMessages(string $rawMessages) : array
    {
        return array();
    }

    public function getMessages(): array
    {
        /**
         * @var $chatList RemoteWebElement
         * @var $chatElements RemoteWebElement[]
         */
        $scannedChats = array();
        $waiter = new WebDriverWait($this->driver, 10);
        $chatList = $waiter->until(EC::presenceOfElementLocated(By::cssSelector("div[aria-label='Chat list']")));
        $totalChats = $chatList->getAttribute("rowcount");

        while (count($totalChats) != $totalChats) {

            $chatElements = $waiter->until(EC::presenceOfAllElementsLocatedBy(By::cssSelector("div._3m_Xw")));
            foreach ($chatElements as $chatElement)
            {
                $chatName = $this->getChatName($chatElement);
                if (in_array($chatName, $totalChats))
                {
                  continue;
                }
                $messages = $this->getMessagesFromChat($chatElement);
                $parsed = $this->parseMessages($messages);
                $totalChats[] = $chatName;
            }
        }
        return array();
    }

    public function quit()
    {
        $this->driver->quit();
    }

}
