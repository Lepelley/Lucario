<?php

namespace Lucario\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AbstractController
{

    private Environment $templateEngine;

    public function __construct()
    {
        $path = defined('TEMPLATE_PATH') ? TEMPLATE_PATH : dirname(__DIR__, 4) . '/templates';
        $loader = new FilesystemLoader($path);
        $this->templateEngine = new Environment($loader);
    }

    protected function render(string $view, array $vars = []): string
    {
        return $this->templateEngine->render($view.'.html.twig', $vars);
    }

    protected function isSubmitted(): bool
    {
        return sizeof($_POST) > 0;
    }
}
