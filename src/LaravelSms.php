<?php


namespace Nazmulpcc\LaravelSms;


use Illuminate\Support\Arr;
use Nazmulpcc\LaravelSms\Contracts\SmsGateway;
use Nazmulpcc\LaravelSms\Exceptions\InvalidGatewayException;
use Nazmulpcc\LaravelSms\Exceptions\InvalidServiceHandler;

class LaravelSms
{
    /**
     * @var string
     */
    protected $driver;

    protected $service;

    /**
     * LaravelSms constructor.
     * @param $driver
     * @throws InvalidGatewayException
     * @throws InvalidServiceHandler
     */
    public function __construct($driver)
    {
        $this->driver = $driver;
        $this->service = $this->resolveDriver($driver);
    }

    /**
     * @param string $driver
     * @param array $config
     * @return SmsGateway
     * @throws InvalidGatewayException
     * @throws InvalidServiceHandler
     */
    protected function resolveDriver($driver, $config = [])
    {
        $service = config("laravel-sms.services.{$driver}");
        if(!$service || !isset($service['handler'])){
            throw new InvalidGatewayException();
        }
        $config = Arr::except($service, 'handler');
        $instance = new $service['handler']($config);
        if(! ($instance instanceof SmsGateway)){
            throw new InvalidServiceHandler();
        }
        return $instance;
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this|mixed
     */
    public function __call($name, $arguments)
    {
        $result = $this->service->$name(...$arguments);
        if($result === $this->service){
            return $this;
        }
        return $result;
    }
}
