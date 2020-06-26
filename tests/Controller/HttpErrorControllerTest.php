<?php

namespace App\Controller;

use Lucario\Controller\AbstractController;
use Lucario\Controller\HttpErrorController;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class HttpErrorControllerTest extends TestCase
{
    public function testNotFoundWithNoTemplate(): void
    {
        $this->assertSame('<h1>404 Not Found</h1>', $this->getController()->notFound());
    }

    public function testMethodNotAllowedWithNoTemplate(): void
    {
        $this->assertSame('<h1>405 Method Not Allowed</h1>', $this->getController()->methodNotAllowed());
    }

    private function getController(): HttpErrorController
    {
//        return new class extends HttpErrorController {
//            public function __construct()
//            {
//                parent::__construct();
//                $this->templateDirectory = __DIR__;
//            }
//        };
        define('TEMPLATE_PATH', dirname(__DIR__));
        return new HttpErrorController();
    }
}
