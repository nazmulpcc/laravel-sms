<?php


namespace Nazmulpcc\LaravelSms\Traits;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Nazmulpcc\LaravelSms\Exceptions\ParameterMismatchException;
use Nazmulpcc\LaravelSms\Facades\LaravelSms;

trait MustVerifyPhoneTrait
{
    /**
     * Phone verification code validity duration in minutes
     * @var int
     */
    protected $codeValidityDuration = 5;

    /**
     * Determine if the user has verified their phone.
     *
     * @return bool
     */
    public function hasVerifiedPhone()
    {
        return ! is_null($this->phone_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Send the email verification notification.
     * @return void
     * @throws ParameterMismatchException
     */
    public function sendPhoneVerificationNotification()
    {
        $target = $this->getPhoneForVerification();
        if(! $target){
            throw new ParameterMismatchException('Invalid phone supplied for verification');
        }
        $message = $this->generatePhoneVerificationMessage(
             $this->getExistingPhoneVerificationCode() ?: $this->generateNewCodeForPhoneVerification()
        );

        return LaravelSms::send($target, $message);
    }

    /**
     * Attempt to verify a phone with a code
     * @param $code
     * @return bool
     */
    public function verifyPhone($code)
    {
        $target = $this->getPhoneForVerification();
        $entry = DB::table('password_resets')
            ->where('email', $target)
            ->where('token', $code)
            ->where('created_at', '>=', now()->subMinutes($this->codeValidityDuration))
            ->first();
        if($entry){
            DB::table('password_resets')
                ->where('email', $target)
                ->delete();
            return $this->markPhoneAsVerified();
        }

        return false;
    }

    /**
     * Get the phone that should be used for verification.
     *
     * @return string
     */
    public function getPhoneForVerification()
    {
        return $this->phone;
    }

    /**
     * Generate and save a new code for verification
     * @return int
     */
    protected function generateNewCodeForPhoneVerification()
    {
        $code = rand(100000, 999999);
        DB::table('password_resets')
            ->insert([
                'email' => $this->getPhoneForVerification(),
                'token' => $code,
                'created_at' => now()
            ]);
        return $code;
    }

    protected function generatePhoneVerificationMessage($code)
    {
        return 'Your OTP for '. config('app.name') . ' is ' . $code;
    }

    /**
     * Get existing phone verification code
     * @return string|null
     */
    protected function getExistingPhoneVerificationCode()
    {
        $phone = $this->getPhoneForVerification();
        $entry = DB::table('password_resets')
            ->where('email', $phone)
            ->where('created_at', '>=', now()->subMinutes($this->codeValidityDuration))
            ->first();

        return data_get($entry, 'token');
    }
}
