<?php

namespace Ockle\GoCardlessWebhook\Events;

class SubscriptionEvent extends AbstractEvent
{
    const ACTION_CREATED = 'created';
    const ACTION_CUSTOMER_APPROVAL_GRANTED = 'customer_approval_granted';
    const ACTION_CUSTOMER_APPROVAL_DENIED = 'customer_approval_denied';
    const ACTION_PAYMENT_CREATED = 'payment_created';
    const ACTION_CANCELLED = 'cancelled';

    /**
     * Get subscription ID
     *
     * @return mixed
     */
    public function getSubscriptionId()
    {
        return $this->data->links->subscription;
    }

    /**
     * Get payment ID
     *
     * @return mixed
     */
    public function getPaymentId()
    {
        return $this->data->links->payment;
    }
}
