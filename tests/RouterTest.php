<?php

namespace Lucario\Tests;

use FastRoute\RouteCollector;
use Lucario\Router;
use PHPUnit\Framework\TestCase;
use Twig\Error\LoaderError;

class RouterTest extends TestCase
{
    public function testRouteWithoutParams(): void
    {
        $router = $this->getRouter();
        $this->assertSame('Home test', $router->dispatch('/', 'GET'));
    }

    public function testRouteWithParams(): void
    {
        $router = $this->getRouter();
        $this->assertSame('Home test', $router->dispatch('/?params', 'GET'));
    }

    public function testRouteWithWrongPath(): void
    {
        $router = $this->getRouter();
        $this->expectException(LoaderError::class);
        $router->dispatch('/doesnt-exist', 'GET');
    }

    public function testRouteWithBadMethod(): void
    {
        $router = $this->getRouter();
        $this->expectException(LoaderError::class);
        $router->dispatch('/', 'FAKE');
    }

    public function testRouteWithController(): void
    {
        $router = $this->getRouter();
        $this->assertSame('Controller test', $router->dispatch('/controller', 'GET'));
    }

    public function testRouteCanThrowException(): void
    {
        $router = $this->getRouter();
        $this->expectException(\Exception::class);
        $router->dispatch('/controllerException', 'GET');
    }

    private function getRouter (): Router
    {
        return new Router(function (RouteCollector $router) {
            $router->get('/', function () {
                return 'Home test';
            });
            $router->get('/controller', 'Lucario\Tests\MyController::print');
            $router->get('/controllerException', 'Lucario\Tests\MyController::printError');
        });
    }
}
