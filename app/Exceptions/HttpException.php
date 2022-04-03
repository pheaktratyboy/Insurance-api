<?php


namespace App\Exceptions;

class HttpException extends \Symfony\Component\HttpKernel\Exception\HttpException
{
    private $title;

    public function __construct(int $statusCode, string $message = null, $title = '', \Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
