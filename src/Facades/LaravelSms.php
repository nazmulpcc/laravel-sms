<?php


namespace Nazmulpcc\LaravelSms\Facades;


use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Facade;

/**
 * Class LaravelSms
 * @package Nazmulpcc\LaravelSms\Facades
 * @method static send(string $target, string $message)
 * @method static queryDeliveryStatus($trackingId = false)
 * @method static string status()
 * @method static Response response()
 */
class LaravelSms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-sms';
    }
}
