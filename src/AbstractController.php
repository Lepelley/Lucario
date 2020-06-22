<?php

namespace Lucario;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AbstractController
{

    private Environment $templateEngine;

    public function __construct()
    {
        $loader = new FilesystemLoader(dirname(__DIR__, 4) . '/templates');
        $this->templateEngine = new Environment($loader);
    }

    protected function render(string $view, array $vars = []): string
    {
        return $this->templateEngine->render($view.'.html.twig', $vars);
    }
}
