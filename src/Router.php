<?php

namespace Lucario;

use FastRoute;
use FastRoute\Dispatcher;

class Router
{
    /**
     * Contains routes of your application
     *
     * @var string|mixed
     */
    private $routes;

    /**
     * Router constructor.
     *
     * @param string|mixed $routes
     */
    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param string $uri
     * @param string $method
     *
     * @return string
     *
     * @throws \Exception
     */
    public function dispatch(string $uri, string $method): string
    {
        $dispatcher = FastRoute\simpleDispatcher($this->routes);

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $routeInfo = $dispatcher->dispatch($method, rawurldecode($uri));

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return (new class extends AbstractController {
                    public function print() : string
                    {
                        return $this->render('errors/404');
                    }
                })->print();
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                return (new class extends AbstractController {
                    public function print() : string
                    {
                        return $this->render('errors/405');
                    }
                })->print();
            case Dispatcher::FOUND:
                // Je vérifie si mon parametre est une chaine de caractere
                $method = [];
                if (is_string($routeInfo[1])) {
                    // si dans la chaine reçu on trouve les ::
                    if (strpos($routeInfo[1], '::') !== false) {
                        //on coupe sur l'operateur de resolution de portée (::)
                        // qui est symbolique ici dans notre chaine de caractere.
                        $route = explode('::', $routeInfo[1]);
                        $method = [new $route[0], $route[1]];
                    } else {
                        // sinon c'est directement la chaine qui nous interesse
                        $method = $routeInfo[1];
                    }
                } elseif(is_callable($routeInfo[1])) {
                    // dans le cas ou c'est appelable (closure (fonction anonyme) par exemple)
                    $method = $routeInfo[1];
                }
                // on execute avec call_user_func_array
                if (is_callable($method)) {
                    return call_user_func_array($method, $routeInfo[2]);
                } else {
                    throw new \Exception(sprintf('Not callable'));
                }
            default:
                throw new \Exception(sprintf('New dispatcher case'));
        }
    }
}
