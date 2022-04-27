<?php


namespace Nazmulpcc\LaravelSms\Services;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Nazmulpcc\LaravelSms\Enums\SmsStatus;
use Nazmulpcc\LaravelSms\Services\Service;

class LocalSms extends Service
{

    public function send($target, $message)
    {
        Log::info("Sending Sms to $target", compact('message'));

        $this->status = SmsStatus::SUCCESS;
        $this->trackingId = Str::random(8);

        return $this;
    }

    public function sendMany($targets, $messages)
    {
        // TODO: Implement sendMany() method.
    }

    public function queryDeliveryStatus($trackingId = false)
    {
        return true;
    }
}
