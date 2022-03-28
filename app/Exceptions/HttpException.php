<?php


namespace App\Exceptions;

class HttpException extends \Symfony\Component\HttpKernel\Exception\HttpException
{
    private $title;

    public function __construct()
    {
        //parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    public function getTitle()
    {
        return $this->title;
    }
}
