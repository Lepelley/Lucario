<?php

namespace App\Tests\Lucario;

use Lucario\Kernel;
use Lucario\Router;
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
