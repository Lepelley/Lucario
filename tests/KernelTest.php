<?php

namespace Lucario\Tests;

use Lucario\Core\Kernel;
use Lucario\Core\Router;
use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    public function testKernel(): void
    {
        $response = 'Hello world!';
        $router = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock();
        $router->method('dispatch')->willReturn($response);

        /** @var Router $router */
        $kernel = new Kernel($router);
        $this->assertSame($response, $kernel->run('/', 'GET'));
    }
}
