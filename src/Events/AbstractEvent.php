<?php

namespace Ockle\GoCardlessWebhook\Events;

use Symfony\Component\EventDispatcher\Event;

abstract class AbstractEvent extends Event
{
    /**
     * @var Event data
     */
    protected $data;

    /**
     * AbstractEvent constructor.
     *
     * @param $data Event data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get event ID
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->data->id;
    }

    /**
     * Get event created at DateTime
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return new \DateTime($this->data->created_at);
    }

    /**
     * Get event origin
     *
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->data->details->origin;
    }

    /**
     * Get event cause
     *
     * @return mixed
     */
    public function getCause()
    {
        return $this->data->details->cause;
    }

    /**
     * Get event description
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->data->details->description;
    }

    /**
     * Get event scheme
     *
     * @return mixed
     */
    public function getScheme()
    {
        return isset($this->data->details->scheme) ? $this->data->details->scheme : null;
    }

    /**
     * Get event reason code
     *
     * @return mixed
     */
    public function getReasonCode()
    {
        return isset($this->data->details->reason_code) ? $this->data->details->reason_code : null;
    }

    /**
     * Get event new customer bank account
     *
     * @return mixed
     */
    public function getNewCustomerBankAccount()
    {
        return isset($this->data->links->new_customer_bank_account) ? $this->data->links->new_customer_bank_account : null;
    }

    /**
     * Get event previous customer bank ccount
     *
     * @return mixed
     */
    public function getPreviousCustomerBankAccount()
    {
        return isset($this->data->links->previous_customer_bank_account) ? $this->data->links->previous_customer_bank_account : null;
    }

    /**
     * Get event organisation
     *
     * @return mixed
     */
    public function getOrganisation()
    {
        return isset($this->data->links->organisation) ? $this->data->links->organisation : null;
    }

    /**
     * Get event parent
     *
     * @return mixed
     */
    public function getParent()
    {
        return isset($this->data->links->parent_event) ? $this->data->links->parent_event : null;
    }

    /**
     * Get event metadata
     *
     * @return mixed
     */
    public function getMetadata()
    {
        return $this->data->metadata;
    }

    /**
     * Check whether the event action is a certain action
     *
     * @param $action
     *
     * @return bool
     */
    public function actionIs($action)
    {
        return $this->data->action === $action;
    }

    /**
     * Get event data as a JSON encoded string
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->data);
    }
}
