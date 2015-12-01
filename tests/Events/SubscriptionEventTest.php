<?php

namespace Ockle\GoCardlessWebhook\Tests\Events;

use Mockery;
use Ockle\GoCardlessWebhook\Events\SubscriptionEvent;

class SubscriptionEventTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetId()
    {
        $event = new SubscriptionEvent($this->getData());

        $this->assertSame('EVTEST8MC38NRB', $event->getId());
    }

    public function testGetCreatedAt()
    {
        $event = new SubscriptionEvent($this->getData());

        $this->assertSame('2015-11-30 22:53:39', $event->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    public function testActionIs()
    {
        $event = new SubscriptionEvent($this->getData());

        $this->assertTrue($event->actionIs(SubscriptionEvent::ACTION_CREATED));
    }

    public function testGetSubscriptionId()
    {
        $event = new SubscriptionEvent($this->getData());

        $this->assertSame('index_ID_123', $event->getSubscriptionId());
    }

    public function testGetPaymentId()
    {
        $event = new SubscriptionEvent($this->getData());

        $this->assertSame('payment_ID_456', $event->getPaymentId());
    }

    public function testGetOrigin()
    {
        $event = new SubscriptionEvent($this->getData());

        $this->assertSame('api', $event->getOrigin());
    }

    public function testGetCause()
    {
        $event = new SubscriptionEvent($this->getData());

        $this->assertSame('subscription_created', $event->getCause());
    }

    public function testGetDescription()
    {
        $event = new SubscriptionEvent($this->getData());

        $this->assertSame('Subscription created via the API.', $event->getDescription());
    }

    public function testGetMetadata()
    {
        $event = new SubscriptionEvent($this->getData());

        $this->assertInternalType('object', $event->getMetadata());
    }

    public function testToString()
    {
        $event = new SubscriptionEvent($this->getData());

        $this->assertSame($this->getJson(), (string) $event);
    }

    protected function getData()
    {
        return json_decode($this->getJson());
    }

    protected function getJson()
    {
        return '{"id":"EVTEST8MC38NRB","created_at":"2015-11-30T22:53:39.875Z","resource_type":"subscriptions","action":"created","links":{"payment":"payment_ID_456","subscription":"index_ID_123"},"details":{"origin":"api","cause":"subscription_created","description":"Subscription created via the API."},"metadata":{}}';
    }
}
