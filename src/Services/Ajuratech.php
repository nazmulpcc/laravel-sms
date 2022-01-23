<?php


namespace Nazmulpcc\LaravelSms\Services;


use Carbon\Carbon;
use Nazmulpcc\LaravelSms\Contracts\SmsGateway;
use Nazmulpcc\LaravelSms\Enums\SmsStatus;
use Nazmulpcc\LaravelSms\Exceptions\InvalidTrackingIdException;
use Nazmulpcc\LaravelSms\Exceptions\ParameterMismatchException;

class Ajuratech extends Service
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $callerId;

    public function __construct($configs)
    {
        $this->setApiKey($configs['api_key'])
            ->setSecretKey($configs['secret_key'])
            ->setCallerId($configs['caller_id']);
    }

    /**
     * @param string $target
     * @param string $message
     * @return \Illuminate\Http\Client\Response|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function send($target, $message)
    {
        $this->response = $this->getClient()
            ->get('http://apismpp.ajuratech.com/sendtext', [
                'apikey' => $this->apiKey,
                'secretkey' => $this->secretKey,
                'callerID' => $this->callerId,
                'toUser' => $target,
                'messageContent' => $message
            ]);

        $response = $this->response->json();
        if($this->response->ok() && data_get($response, 'Text') === 'ACCEPTD'){
            $this->status = SmsStatus::SUCCESS;
            $this->trackingId = data_get($response, 'Message_ID');
        }else{
            $this->status = SmsStatus::REJECTED;
            $this->trackingId = false;
        }

        return $this;
    }

    /**
     * @param string|boolean $trackingId
     * @return array|string
     * @throws InvalidTrackingIdException
     */
    public function queryDeliveryStatus($trackingId = false)
    {
        $trackingId = $trackingId ?: $this->trackingId;
        throw_if(!$trackingId, new InvalidTrackingIdException());
        $this->response = $this->getClient()
            ->get('https://smpp.ajuratech.com:7790/getstatus', [
                'apikey' => $this->apiKey,
                'secretkey' => $this->secretKey,
                'messageid' => $trackingId
            ]);
        $response = $this->response->json();
        if($this->response->ok() && $response['Status'] == '0'){
            $this->status = SmsStatus::SUCCESS;
            return [
                'status' => $response['Text'] === 'DELIVRD' ? SmsStatus::DELIVERED : SmsStatus::FAILED,
                'delivered_at' => Carbon::createFromTimestamp(data_get($response, 'Delivery Time')),
            ];
        }
        return [
            'status' => SmsStatus::FAILED
        ];
    }

    public function sendMany($targets, $messages)
    {
        // TODO: Implement sendMany() method.
    }

    /**
     * @param $key
     * @return $this
     */
    public function setApiKey($key)
    {
        $this->apiKey = $key;
        return $this;
    }

    /**
     * @param $key
     * @return $this
     */
    public function setSecretKey($key)
    {
        $this->secretKey = $key;
        return $this;
    }

    /**
     * @param string $callerId
     * @return $this
     */
    public function setCallerId($callerId)
    {
        $this->callerId = $callerId;
        return $this;
    }
}
