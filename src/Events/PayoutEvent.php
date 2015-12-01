<?php

namespace Ockle\GoCardlessWebhook\Events;

class PayoutEvent extends AbstractEvent
{
    const ACTION_PAID = 'paid';

    /**
     * Get payout ID
     *
     * @return mixed
     */
    public function getPayoutId()
    {
        return $this->data->links->payout;
    }
}
