<?php

namespace App;

use \Spatie\WebhookClient\ProcessWebhookJob as SpatieProcessWebhookJob;

class StripeWebhooksHandler extends SpatieProcessWebhookJob
{
    public function handle()
    {
        // $this->webhookCall // contains an instance of `WebhookCall`

        // perform the work here
        switch($this->webhookCall->payload['type']) {
            case "checkout.session.completed" :
                \App\StripeWebhooks\SessionCompletedWebhook::handle($this->webhookCall->payload['data']['object']);
            break;
            case "payment_intent.succeeded" :
                \App\StripeWebhooks\PaymentSucceededWebhook::handle($this->webhookCall->payload['data']['object']);
            break;
            case "charge.refunded" :
                \App\StripeWebhooks\ChargeRefundedWebhook::handle($this->webhookCall->payload['data']['object']);
            break;
            case "application_fee.refunded" :
                \App\StripeWebhooks\ApplicationFeeRefundedWebhook::handle($this->webhookCall->payload['data']['object']);
            break;
        }
        
    }
}