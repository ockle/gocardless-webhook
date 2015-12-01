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
