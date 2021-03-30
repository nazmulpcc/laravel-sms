<?php


namespace Nazmulpcc\LaravelSms\Exceptions;


use Exception;
use Throwable;

class ParameterMismatchException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message !== '' ? $message : 'Parameter types don\'t match';
        parent::__construct($message, $code, $previous);
    }
}
