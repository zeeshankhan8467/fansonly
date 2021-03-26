<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'stripe/*',
        'subscribe/paypal-notify/*',
        'tip/paypal/*',
        'tip/card/*',
        'tip/ccbill',
        'tip/ccbill/approval',
        'ccbill*',
        'tip/paystack',
        'paystack/webhooks',
        'paystack/store-authorization',
        'webpayplus/returnUrl',
        'subscribe/process-webpayplus',
        'paytm/webhooks',
        'paytm/callback'
    ];
}
