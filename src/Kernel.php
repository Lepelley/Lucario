<?php

namespace Lucario;

use FastRoute;

class Kernel
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param string $uri
     * @param string $method
     *
     * @return string
     *
     * @throws \Exception
     */
    public function run($uri, $method): ?string
    {
        return $this->router->dispatch($uri, $method);
    }
}
