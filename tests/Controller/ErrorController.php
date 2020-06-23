<?php

namespace App\Controller;

use Lucario\AbstractController;

class ErrorController extends AbstractController
{
    /**
     * Error 404
     */
    public function notFound(): string
    {
        return $this->render('errors/404');
    }

    /**
     * Error 405
     */
    public function methodForbidden(): string
    {
        return $this->render('errors/405');
    }
}