<?php


namespace Nazmulpcc\LaravelSms\Services;


use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Nazmulpcc\LaravelSms\Contracts\SmsGateway;

abstract class Service implements SmsGateway
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $trackingId = false;

    public abstract function send($target, $message);

    public abstract function sendMany($targets, $messages);

    /**
     * Get the last response
     * @return Response
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * Get the last request status
     * A value from SmsStatus is returned
     * @return string
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function trackingId()
    {
        return $this->trackingId;
    }

    /**
     * @return Http
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getClient()
    {
        return app()->make(HttpClient::class);
    }
}
