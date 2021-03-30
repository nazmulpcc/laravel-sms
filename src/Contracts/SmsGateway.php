<?php


namespace Nazmulpcc\LaravelSms\Contracts;


use Nazmulpcc\LaravelSms\Exceptions\ParameterMismatchException;

interface SmsGateway
{
    /**
     * @param string $target
     * @param string $message
     * @return mixed
     */
    public function send($target, $message);

    /**
     * @param false $trackingId
     * @return array
     */
    public function queryDeliveryStatus($trackingId = false);
}
