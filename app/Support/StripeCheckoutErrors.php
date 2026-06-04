<?php

namespace App\Support;

use Stripe\Exception\AuthenticationException;
use Throwable;

class StripeCheckoutErrors
{
    public static function userFacingMessage(Throwable $exception): string
    {
        if ($exception instanceof AuthenticationException) {
            $message = $exception->getMessage();

            if (self::isIpRestrictionError($message)) {
                return 'Stripe blocked this request: your secret key is limited to certain IP addresses. '
                    .'In Stripe Dashboard → Developers → API keys, open your secret key and remove IP restrictions '
                    .'(or add your current public IP) while testing locally.';
            }

            return 'Stripe authentication failed. Verify STRIPE_SECRET in .env matches the secret key in your Stripe Dashboard (test: sk_test_...).';
        }

        return 'Unable to start Stripe checkout at the moment.';
    }

    private static function isIpRestrictionError(string $message): bool
    {
        $lower = strtolower($message);

        return str_contains($lower, 'ip address')
            || str_contains($lower, 'does not allow requests from');
    }
}
