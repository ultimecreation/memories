<?php
declare(strict_types=1);

class ErrorController extends Controller
{
    /**
     * pageNotFound
     *
     * @return void
     */
    public function pageNotFound(): void
    {
        echo 'Page inexistante';
    }
}
