<?php

namespace Ockle\GoCardlessWebhook;

use Ockle\GoCardlessWebhook\Events\MandateEvent;
use Ockle\GoCardlessWebhook\Events\PaymentEvent;
use Ockle\GoCardlessWebhook\Events\PayoutEvent;
use Ockle\GoCardlessWebhook\Events\RefundEvent;
use Ockle\GoCardlessWebhook\Events\SubscriptionEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Closure;

class Service
{
    const EVENT_PAYMENT = 'payments';
    const EVENT_MANDATE = 'mandates';
    const EVENT_PAYOUT = 'payouts';
    const EVENT_SUBSCRIPTION = 'subscriptions';
    const EVENT_REFUND = 'refunds';

    const RESPONSE_OK = 204;
    const RESPONSE_INVALID_SIGNATURE = 498;
    const RESPONSE_BAD_REQUEST = 400;

    /**
     * @var string GoCardless webhook secret key
     */
    protected $key;

    /**
     * @var EventDispatcherInterface Event dispatcher
     */
    protected $dispatcher;

    /**
     * @var integer HTTP status code to be returned to GoCardless
     */
    private $responseStatus;

    /**
     * Service constructor.
     *
     * @param string                   $key        GoCardless webhook secret key
     * @param EventDispatcherInterface $dispatcher Event dispatcher
     */
    public function __construct($key, EventDispatcherInterface $dispatcher)
    {
        $this->key = $key;
        $this->dispatcher = $dispatcher;
    }

    /**
     * The main methd to call to process an incoming webhook
     *
     * @param string $signature Signature sent by GoCardless in the request header
     * @param string $postData JSON string sent by GoCardless
     *
     * @return bool True on success, false on failure
     * @throws \Exception
     */
    public function process($signature, $postData)
    {
        // Verify the request is signed correctly
        if (!$this->verify($signature, $postData)) {
            $this->responseStatus = static::RESPONSE_INVALID_SIGNATURE;

            return false;
        }

        // Decode the JSON and return false if it's malformed
        $data = json_decode($postData);

        if (is_null($data)) {
            $this->responseStatus = static::RESPONSE_BAD_REQUEST;

            return false;
        }

        // A webhook can contain multiple events, so fire dispatch them
        foreach ($data->events as $eventData) {
            switch ($eventData->resource_type) {
                case static::EVENT_PAYMENT:
                    $this->dispatcher->dispatch(static::EVENT_PAYMENT, new PaymentEvent($eventData));

                    break;
                case static::EVENT_MANDATE:
                    $this->dispatcher->dispatch(static::EVENT_MANDATE, new MandateEvent($eventData));

                    break;
                case static::EVENT_PAYOUT:
                    $this->dispatcher->dispatch(static::EVENT_PAYOUT, new PayoutEvent($eventData));

                    break;
                case static::EVENT_SUBSCRIPTION:
                    $this->dispatcher->dispatch(static::EVENT_SUBSCRIPTION, new SubscriptionEvent($eventData));

                    break;
                case static::EVENT_REFUND:
                    $this->dispatcher->dispatch(static::EVENT_REFUND, new RefundEvent($eventData));

                    break;
            }
        }

        // All is well
        $this->responseStatus = static::RESPONSE_OK;

        return true;
    }

    /**
     * Attach a listener on the payment event
     *
     * @param Closure $listener Function to run when a payment event is received
     *
     * @return $this
     */
    public function onPayment(Closure $listener)
    {
        $this->dispatcher->addListener(static::EVENT_PAYMENT, $listener);

        return $this;
    }

    /**
     * Attach a listener on the mandate event
     *
     * @param Closure $listener Function to run when a mandate event is received
     *
     * @return $this
     */
    public function onMandate(Closure $listener)
    {
        $this->dispatcher->addListener(static::EVENT_MANDATE, $listener);

        return $this;
    }

    /**
     * Attach a listener on the payout event
     *
     * @param Closure $listener Function to run when a payout event is received
     *
     * @return $this
     */
    public function onPayout(Closure $listener)
    {
        $this->dispatcher->addListener(static::EVENT_PAYOUT, $listener);

        return $this;
    }

    /**
     * Attach a listener on the subscription event
     *
     * @param Closure $listener Function to run when a subscription event is received
     *
     * @return $this
     */
    public function onSubscription(Closure $listener)
    {
        $this->dispatcher->addListener(static::EVENT_SUBSCRIPTION, $listener);

        return $this;
    }

    /**
     * Attach a listener on the refund event
     *
     * @param Closure $listener Function to run when a refund event is received
     *
     * @return $this
     */
    public function onRefund(Closure $listener)
    {
        $this->dispatcher->addListener(static::EVENT_REFUND, $listener);

        return $this;
    }

    /**
     * Get the HTTP status code that should be returned to GoCardless
     *
     * @return int HTTP status code
     */
    public function getResponseStatus()
    {
        return $this->responseStatus;
    }

    /**
     * Verify the request
     *
     * @param string $signature Signature sent by GoCardless in the request header
     * @param string $postData JSON string sent by GoCardless
     *
     * @return bool True on success, false on failure
     * @throws \Exception
     */
    protected function verify($signature, $postData)
    {
        $hash = hash_hmac('sha256', $postData, $this->key);

        if ($hash === false) {
            throw new \Exception('SHA256 not available');
        }

        return hash_equals($hash, $signature);
    }
}
