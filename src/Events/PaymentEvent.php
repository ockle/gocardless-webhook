<?php

namespace Ockle\GoCardlessWebhook\Events;

class PaymentEvent extends AbstractEvent
{
    const ACTION_CREATED = 'created';
    const ACTION_CUSTOMER_APPROVAL_GRANTED = 'customer_approval_granted';
    const ACTION_CUSTOMER_APPROVAL_DENIED = 'customer_approval_denied';
    const ACTION_SUBMITTED = 'submitted';
    const ACTION_CONFIRMED = 'confirmed';
    const ACTION_CHARGEBACK_CANCELLED = 'chargeback_cancelled';
    const ACTION_PAID_OUT = 'paid_out';
    const ACTION_LATE_FAILURE_SETTLED = 'late_failure_settled';
    const ACTION_CHARGEBACK_SETTLED = 'chargeback_settled';
    const ACTION_FAILED = 'failed';
    const ACTION_CHARGED_BACK = 'charged_back';
    const ACTION_CANCELLED = 'cancelled';
    const ACTION_RESUBMISSION_REQUESTED = 'resubmission_requested';

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
