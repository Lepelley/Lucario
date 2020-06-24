<?php

namespace Lucario\Tests;

use FastRoute\RouteCollector;
use Lucario\Router;
use PHPUnit\Framework\TestCase;

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

    /**
     * @runInSeparateProcess
     */
    public function testRouteWithWrongPathWithController(): void
    {
        define('TEMPLATE_PATH', __DIR__.DIRECTORY_SEPARATOR.'templates');
        $router = $this->getRouter();
        $this->assertSame('error 404', $router->dispatch('/doesnt-exist', 'GET'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testRouteWithBadMethodWithController(): void
    {
        define('TEMPLATE_PATH', __DIR__.DIRECTORY_SEPARATOR.'templates');
        $router = $this->getRouter();
        $this->assertSame('error 405', $router->dispatch('/', 'FAKE'));
    }

    public function testRouteWithController(): void
    {
        $router = $this->getRouter();
        $this->assertSame('Controller test', $router->dispatch('/controller', 'GET'));
    }

    public function testRouteWithControllerWithWrongFunction(): void
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
            $router->get('/test-no-method', 'Lucario\Tests\MyController::printNoExisting');
        });
    }
}
