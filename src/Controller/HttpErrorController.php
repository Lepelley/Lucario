<?php

namespace Lucario\Controller;

class HttpErrorController extends AbstractController
{
    /**
     * Error 404
     */
    public function notFound(): string
    {
        http_response_code(404);
        if (file_exists($this->templatesDirectory.'/errors/404.html.twig')) {
            return $this->render('errors/404');
        }

        return '<h1>404 Not Found</h1>';
    }

    /**
     * Error 405
     */
    public function methodNotAllowed(): string
    {
        http_response_code(405);
        if (file_exists($this->templatesDirectory.'/errors/405.html.twig')) {
            return $this->render('errors/405');
        }

        return '<h1>405 Method Not Allowed</h1>';
    }
}
