<?php

namespace Ockle\GoCardlessWebhook\Tests\Events;

use Mockery;
use Ockle\GoCardlessWebhook\Events\RefundEvent;

class RefundEventTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetId()
    {
        $event = new RefundEvent($this->getData());

        $this->assertSame('EVTESTG53JCVBS', $event->getId());
    }

    public function testGetCreatedAt()
    {
        $event = new RefundEvent($this->getData());

        $this->assertSame('2015-11-30 22:52:44', $event->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    public function testActionIs()
    {
        $event = new RefundEvent($this->getData());

        $this->assertTrue($event->actionIs(RefundEvent::ACTION_CREATED));
    }

    public function testGetRefundId()
    {
        $event = new RefundEvent($this->getData());

        $this->assertSame('index_ID_123', $event->getRefundId());
    }

    public function testGetOrigin()
    {
        $event = new RefundEvent($this->getData());

        $this->assertSame('api', $event->getOrigin());
    }

    public function testGetCause()
    {
        $event = new RefundEvent($this->getData());

        $this->assertSame('payment_refunded', $event->getCause());
    }

    public function testGetDescription()
    {
        $event = new RefundEvent($this->getData());

        $this->assertSame('The refund has been created, and will be submitted in the next batch.', $event->getDescription());
    }

    public function testGetMetadata()
    {
        $event = new RefundEvent($this->getData());

        $this->assertInternalType('object', $event->getMetadata());
    }

    public function testToString()
    {
        $event = new RefundEvent($this->getData());

        $this->assertSame($this->getJson(), (string) $event);
    }

    protected function getData()
    {
        return json_decode($this->getJson());
    }

    protected function getJson()
    {
        return '{"id":"EVTESTG53JCVBS","created_at":"2015-11-30T22:52:44.431Z","resource_type":"refunds","action":"created","links":{"refund":"index_ID_123"},"details":{"origin":"api","cause":"payment_refunded","description":"The refund has been created, and will be submitted in the next batch."},"metadata":{}}';
    }
}
