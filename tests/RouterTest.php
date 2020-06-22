<?php

namespace App\Tests\Lucario;

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

    private function getRouter () {
        return new Router(function (RouteCollector $router) {
            $router->get('/', function () {
                return 'Home test';
            });
        });
    }
}
