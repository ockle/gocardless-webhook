<?php

namespace Ockle\GoCardlessWebhook\Events;

class MandateEvent extends AbstractEvent
{
    const ACTION_CREATED = 'created';
    const ACTION_ACTIVE = 'active';
    const ACTION_CANCELLED = 'cancelled';
    const ACTION_FAILED = 'failed';
    const ACTION_TRANSFERRED = 'transferred';
    const ACTION_EXPIRED = 'expired';
    const ACTION_SUBMITTED = 'submitted';
    const ACTION_RESUBMISSION_REQUESTED = 'resubmission_requested';
    const ACTION_REINSTATED = 'reinstated';

    /**
     * Get mandate ID
     *
     * @return mixed
     */
    public function getMandateId()
    {
        return $this->data->links->mandate;
    }
}
