<?php
namespace PHPConference\Tests;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;


class SeleniumTest extends TestCase
{
    /**
     * @var RemoteWebDriver
     */
    private $driver;

    public function setUp(): void
    {
        parent::setUp();

        $this->driver = $this->driver ?? RemoteWebDriver::create(
            'http://selenium:4444/wd/hub',
            (new ChromeOptions())
                ->setBinary(
                    $_ENV['CHROME_DRIVER'] ?? '/usr/bin/google-chrome-stable',
                )
                ->addArguments(
                    [
                        '--ignore-certificate-errors',
                        '--no-pings',
                        '--no-sandbox',
                    ]
                )
                ->toCapabilities()
            ,
            5000,
            5000
        );
    }

    public function testHasCSRFToken()
    {
        // Load original content
        $this->driver->get('http://php:12001');

        // Waiting for DOMContentLoaded
        $this->driver->wait();

        // Find message box
        $messageElement = $this->driver->findElement(
            WebDriverBy::cssSelector('[name="message"]')
        );

        // Input
        $messageElement->sendKeys('Hello World!');

        $buttonElement = $this->driver->findElement(
            WebDriverBy::cssSelector('.form [type="submit"]')
        );

        $buttonElement->click();

        // Waiting 1 sec
        $this->driver->wait(1);

        // Get flash message
        $flashMessageElement = $this->driver->findElement(
            WebDriverBy::cssSelector('.flash-message')
        );

        // Assert the sent text.
        $this->assertSame(
            'Hello World!が送られてきました。',
            $flashMessageElement->getText()
        );
    }

    public function testHasNotCSRFToken()
    {
        $this->markTestIncomplete('Incomplete.');
    }
}
