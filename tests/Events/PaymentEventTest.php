<?php

namespace Ockle\GoCardlessWebhook\Tests\Events;

use Mockery;
use Ockle\GoCardlessWebhook\Events\PaymentEvent;

class PaymentEventTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetId()
    {
        $event = new PaymentEvent($this->getData());

        $this->assertSame('EVTESTHXHH665M', $event->getId());
    }

    public function testGetCreatedAt()
    {
        $event = new PaymentEvent($this->getData());

        $this->assertSame('2015-11-30 22:51:00', $event->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    public function testActionIs()
    {
        $event = new PaymentEvent($this->getData());

        $this->assertTrue($event->actionIs(PaymentEvent::ACTION_CREATED));
    }

    public function testGetPaymentId()
    {
        $event = new PaymentEvent($this->getData());

        $this->assertSame('index_ID_123', $event->getPaymentId());
    }

    public function testGetOrigin()
    {
        $event = new PaymentEvent($this->getData());

        $this->assertSame('api', $event->getOrigin());
    }

    public function testGetCause()
    {
        $event = new PaymentEvent($this->getData());

        $this->assertSame('payment_created', $event->getCause());
    }

    public function testGetDescription()
    {
        $event = new PaymentEvent($this->getData());

        $this->assertSame('Payment created via the API.', $event->getDescription());
    }

    public function testGetMetadata()
    {
        $event = new PaymentEvent($this->getData());

        $this->assertInternalType('object', $event->getMetadata());
    }

    public function getData()
    {
        return json_decode('{"id":"EVTESTHXHH665M","created_at":"2015-11-30T22:51:00.463Z","resource_type":"payments","action":"created","links":{"payment":"index_ID_123"},"details":{"origin":"api","cause":"payment_created","description":"Payment created via the API."},"metadata":{}}');
    }
}
