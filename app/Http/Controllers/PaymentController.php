<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tenant;
use App\Models\TenantLifecycleLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;
use UnexpectedValueException;

class PaymentController extends Controller
{
    private const CHECKOUT_MAX_AMOUNT_PHP = 500000;

    public function showCheckoutForm()
    {
        if (! $this->isStripeCheckoutConfigured()) {
            abort(503, 'Stripe checkout is not configured.');
        }

        return view('payments.checkout');
    }

    public function checkout(Request $request): RedirectResponse
    {
        if (! $this->isStripeCheckoutConfigured()) {
            return back()->withErrors([
                'stripe' => 'Stripe checkout is unavailable. Please contact support.',
            ])->withInput();
        }

        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1', 'max:'.self::CHECKOUT_MAX_AMOUNT_PHP],
        ]);

        $productName = trim(strip_tags((string) $validated['product_name']));
        $amountInPesos = (float) $validated['amount'];
        $amountInCentavos = (int) round($amountInPesos * 100);

        if ($amountInCentavos < 100) {
            return back()->withErrors([
                'amount' => 'Amount must be at least PHP 1.00.',
            ])->withInput();
        }

        try {
            $stripe = new StripeClient((string) config('services.stripe.secret'));

            $session = $stripe->checkout->sessions->create([
                'mode' => 'payment',
                'success_url' => route('payments.success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payments.cancel'),
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'php',
                        'product_data' => [
                            'name' => $productName,
                        ],
                        'unit_amount' => $amountInCentavos,
                    ],
                    'quantity' => 1,
                ]],
                'metadata' => [
                    'product_name' => $productName,
                    'amount_pesos' => number_format($amountInPesos, 2, '.', ''),
                ],
            ]);

            return redirect()->away((string) $session->url);
        } catch (\Throwable $exception) {
            Log::error('Stripe checkout session creation failed.', [
                'error' => $exception->getMessage(),
                'product_name' => $productName,
                'amount_pesos' => $amountInPesos,
            ]);

            return back()->withErrors([
                'stripe' => 'Unable to start Stripe Checkout right now. Check logs and try again.',
            ])->withInput();
        }
    }

    public function success(Request $request): RedirectResponse
    {
        if (! $this->isStripeCheckoutConfigured()) {
            return redirect()->route('payments.form')->withErrors([
                'stripe' => 'Stripe checkout is unavailable. Please contact support.',
            ]);
        }

        $validated = $request->validate([
            'session_id' => ['required', 'string'],
        ]);

        try {
            $stripe = new StripeClient((string) config('services.stripe.secret'));
            $session = $stripe->checkout->sessions->retrieve($validated['session_id']);
        } catch (\Throwable $exception) {
            Log::error('Stripe session retrieval failed on success page.', [
                'error' => $exception->getMessage(),
                'session_id' => $validated['session_id'],
            ]);

            return redirect()->route('payments.form')->withErrors([
                'stripe' => 'Payment verification failed. Please contact support if you were charged.',
            ]);
        }

        $paymentStatus = (string) ($session->payment_status ?? 'unknown');
        $productName = (string) ($session->metadata->product_name ?? 'N/A');
        $amountTotal = isset($session->amount_total) ? ((int) $session->amount_total / 100) : 0;

        if ($paymentStatus !== 'paid') {
            return redirect()->route('payments.form')->withErrors([
                'stripe' => 'Payment is not marked as paid yet. Please check your transactions.',
            ]);
        }

        return redirect()->route('payments.form')->with('success', sprintf(
            'Payment successful for %s (PHP %s).',
            $productName,
            number_format($amountTotal, 2)
        ));
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('payments.form')->with('info', 'Payment was canceled. No charge was completed.');
    }

    public function webhook(Request $request)
    {
        $payload = (string) $request->getContent();
        $signature = (string) $request->header('Stripe-Signature', '');
        $webhookSecret = (string) config('services.stripe.webhook_secret');

        if ($webhookSecret === '') {
            Log::warning('Stripe webhook received without configured STRIPE_WEBHOOK_SECRET.');

            return response('Webhook secret is not configured.', 500);
        }

        try {
            $event = Webhook::constructEvent($payload, $signature, $webhookSecret);
        } catch (UnexpectedValueException $exception) {
            Log::warning('Invalid Stripe webhook payload.', ['error' => $exception->getMessage()]);

            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $exception) {
            Log::warning('Invalid Stripe webhook signature.', ['error' => $exception->getMessage()]);

            return response('Invalid signature', 400);
        } catch (\Throwable $exception) {
            Log::error('Unexpected Stripe webhook error.', ['error' => $exception->getMessage()]);

            return response('Webhook error', 500);
        }

        if ($event->type === 'checkout.session.completed') {
            /** @var Session $session */
            $session = $event->data->object;

            Log::info('Stripe checkout.session.completed received.', [
                'event_id' => $event->id,
                'session_id' => $session->id ?? null,
                'payment_status' => $session->payment_status ?? null,
                'amount_total' => $session->amount_total ?? null,
                'currency' => $session->currency ?? null,
                'metadata' => $session->metadata ?? [],
            ]);

            $paymentType = (string) ($session->metadata->payment_type ?? '');
            $bookingId = (int) ($session->metadata->booking_id ?? 0);
            $tenantId = (int) ($session->metadata->tenant_id ?? 0);
            $ownerUserId = (int) ($session->metadata->owner_user_id ?? 0);

            if ($paymentType === 'booking' && $bookingId > 0 && $tenantId > 0) {
                $tenant = Tenant::query()->find($tenantId);

                if (! $tenant) {
                    Log::warning('Stripe booking webhook tenant not found.', [
                        'event_id' => $event->id,
                        'tenant_id' => $tenantId,
                        'booking_id' => $bookingId,
                    ]);
                } else {
                    try {
                        $tenant->execute(function () use ($bookingId, $session): void {
                            $booking = Booking::query()->find($bookingId);
                            if (! $booking) {
                                Log::warning('Stripe booking webhook could not find booking.', [
                                    'booking_id' => $bookingId,
                                    'session_id' => $session->id ?? null,
                                ]);

                                return;
                            }

                            if ($booking->paid_at !== null && $booking->payment_channel === 'stripe') {
                                return;
                            }

                            $paymentStatus = (string) ($session->payment_status ?? '');
                            if ($paymentStatus !== 'paid') {
                                Log::warning('Stripe booking webhook received non-paid status.', [
                                    'booking_id' => $bookingId,
                                    'session_id' => $session->id ?? null,
                                    'payment_status' => $paymentStatus,
                                ]);

                                return;
                            }

                            $booking->update([
                                'payment_channel' => 'stripe',
                                'payment_method' => 'stripe_checkout',
                                'payment_reference' => (string) ($session->payment_intent ?? $session->id ?? ''),
                                'paid_at' => now(),
                            ]);
                        });
                    } catch (\Throwable $exception) {
                        Log::error('Stripe booking webhook failed while updating booking.', [
                            'event_id' => $event->id,
                            'tenant_id' => $tenantId,
                            'booking_id' => $bookingId,
                            'error' => $exception->getMessage(),
                        ]);
                    }
                }
            }

            if ($paymentType === 'tenant_onboarding' && $tenantId > 0) {
                $tenant = Tenant::query()->find($tenantId);

                if (! $tenant) {
                    Log::warning('Stripe onboarding webhook tenant not found.', [
                        'event_id' => $event->id,
                        'tenant_id' => $tenantId,
                    ]);
                } else {
                    try {
                        $paymentStatus = (string) ($session->payment_status ?? '');
                        if ($paymentStatus !== 'paid') {
                            Log::warning('Stripe onboarding webhook received non-paid status.', [
                                'tenant_id' => $tenantId,
                                'session_id' => $session->id ?? null,
                                'payment_status' => $paymentStatus,
                            ]);
                        } else {
                            $currentStatus = (string) ($tenant->onboarding_status ?? '');
                            $beforeState = [
                                'onboarding_status' => $currentStatus,
                                'payment_reference' => (string) ($tenant->payment_reference ?? ''),
                                'onboarding_payment_channel' => (string) ($tenant->onboarding_payment_channel ?? ''),
                            ];

                            // Idempotent webhook handling: ignore duplicates once tenant is already pending/approved with stripe.
                            $alreadyRecorded = in_array($currentStatus, [Tenant::ONBOARDING_PENDING_APPROVAL, Tenant::ONBOARDING_APPROVED], true)
                                && (string) ($tenant->onboarding_payment_channel ?? '') === 'stripe'
                                && (string) ($tenant->onboarding_stripe_session_id ?? '') === (string) ($session->id ?? '');

                            if (! $alreadyRecorded) {
                                $tenant->update([
                                    'onboarding_status' => $currentStatus === Tenant::ONBOARDING_APPROVED ? Tenant::ONBOARDING_APPROVED : Tenant::ONBOARDING_PENDING_APPROVAL,
                                    'payment_submitted_at' => $tenant->payment_submitted_at ?? now(),
                                    'onboarding_payment_channel' => 'stripe',
                                    'onboarding_stripe_session_id' => (string) ($session->id ?? ''),
                                    'payment_reference' => (string) ($session->payment_intent ?? $session->id ?? $tenant->payment_reference),
                                ]);

                                TenantLifecycleLog::create([
                                    'tenant_id' => $tenant->id,
                                    'actor_user_id' => $ownerUserId > 0 ? $ownerUserId : null,
                                    'action' => 'tenant.payment.submitted',
                                    'reason' => 'Stripe webhook confirmed onboarding payment.',
                                    'before_state' => $beforeState,
                                    'after_state' => [
                                        'onboarding_status' => $tenant->onboarding_status,
                                        'payment_reference' => $tenant->payment_reference,
                                        'payment_submitted_at' => $tenant->payment_submitted_at?->toDateTimeString(),
                                        'onboarding_payment_channel' => $tenant->onboarding_payment_channel,
                                        'onboarding_stripe_session_id' => $tenant->onboarding_stripe_session_id,
                                    ],
                                ]);
                            }
                        }
                    } catch (\Throwable $exception) {
                        Log::error('Stripe onboarding webhook failed while updating tenant.', [
                            'event_id' => $event->id,
                            'tenant_id' => $tenantId,
                            'owner_user_id' => $ownerUserId,
                            'error' => $exception->getMessage(),
                        ]);
                    }
                }
            }
        } else {
            Log::debug('Unhandled Stripe webhook event type.', [
                'event_id' => $event->id,
                'type' => $event->type,
            ]);
        }

        return response('Webhook received', 200);
    }

    private function isStripeCheckoutConfigured(): bool
    {
        return trim((string) config('services.stripe.secret')) !== ''
            && trim((string) config('services.stripe.key')) !== '';
    }
}
