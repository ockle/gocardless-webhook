<?php

namespace Ockle\GoCardlessWebhook\Events;

class RefundEvent extends AbstractEvent
{
    const ACTION_CREATED = 'created';
    const ACTION_PAID = 'paid';
    const ACTION_REFUND_SETTLED = 'refund_settled';

    /**
     * Get refund ID
     *
     * @return mixed
     */
    public function getRefundId()
    {
        return $this->data->links->refund;
    }
}
