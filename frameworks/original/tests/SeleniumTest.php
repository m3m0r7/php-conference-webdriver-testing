<?php
namespace PHPConference\Tests;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use GuzzleHttp\Client;
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

    public function tearDown(): void
    {
        parent::tearDown();
        $this->driver->quit();
        unset($this->driver);
    }


    public function testDeadLinks()
    {
        // Load original content
        $this->driver->get('http://php:12001/deadlink_test.php');

        // Waiting for DOMContentLoaded
        $this->driver->wait();

        // get links
        $links = $this->driver->findElements(
            WebDriverBy::cssSelector('a')
        );

        $client = new Client();

        // Start to check to alive links
        foreach ($links as $link) {

            $link = $link->getAttribute('href');

            // Send a ping.
            $statusCode = $client
                ->get(
                    $link,
                    [
                        'http_errors' => false,
                    ]
                )
                ->getStatusCode();

            // Check status code
            $this->assertLessThanOrEqual(
                400,
                $statusCode,
                $link . ' returns invalid status code ' . $statusCode);


        }

    }

    public function testHasAddedByJS()
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

    public function testHasNotAddedByJS()
    {
        // Load original content
        $this->driver->get('http://php:12001');

        // Waiting for DOMContentLoaded
        $this->driver->wait();

        // Remove added_by_js
        $this->driver->executeScript('document.querySelector(\'[name=\\\'added_by_js\\\']\').remove();');

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

        // Assert.
        $this->assertSame(
            'added_by_js が送られてきていません。',
            $flashMessageElement->getText()
        );
    }
}
