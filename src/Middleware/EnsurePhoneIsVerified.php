<?php


namespace Nazmulpcc\LaravelSms\Middleware;


use Illuminate\Support\Facades\Redirect;
use Nazmulpcc\LaravelSms\Contracts\MustVerifyPhone;

class EnsurePhoneIsVerified
{
    public function handle($request, \Closure $next,  $redirectToRoute = null)
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyPhone &&
                ! $request->user()->hasVerifiedPhone())) {
            return $request->expectsJson()
                ? abort(403, 'Your phone is not verified.')
                : Redirect::route($redirectToRoute ?: 'phone-verification.notice');
        }
        return $next($request);
    }
}
