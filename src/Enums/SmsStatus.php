<?php


namespace Nazmulpcc\LaravelSms\Enums;


interface SmsStatus
{
    const SUCCESS = 'success';

    const FAILED = 'failed';

    const REJECTED = 'rejected';

    const QUEUED = 'queued';

    const DELIVERED = 'delivered';
}
