<?php

namespace Ockle\GoCardlessWebhook\Tests\Events;

use Mockery;
use Ockle\GoCardlessWebhook\Events\MandateEvent;

class MandateEventTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetId()
    {
        $event = new MandateEvent($this->getData());

        $this->assertSame('EVTESTXDTY7F4S', $event->getId());
    }

    public function testGetCreatedAt()
    {
        $event = new MandateEvent($this->getData());

        $this->assertSame('2015-11-30 22:20:07', $event->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    public function testActionIs()
    {
        $event = new MandateEvent($this->getData());

        $this->assertTrue($event->actionIs(MandateEvent::ACTION_CREATED));
    }

    public function testGetMandateId()
    {
        $event = new MandateEvent($this->getData());

        $this->assertSame('index_ID_123', $event->getMandateId());
    }

    public function testGetOrigin()
    {
        $event = new MandateEvent($this->getData());

        $this->assertSame('api', $event->getOrigin());
    }

    public function testGetCause()
    {
        $event = new MandateEvent($this->getData());

        $this->assertSame('mandate_created', $event->getCause());
    }

    public function testGetDescription()
    {
        $event = new MandateEvent($this->getData());

        $this->assertSame('Mandate created via the API.', $event->getDescription());
    }

    public function testGetMetadata()
    {
        $event = new MandateEvent($this->getData());

        $this->assertInternalType('object', $event->getMetadata());
    }

    public function testToString()
    {
        $event = new MandateEvent($this->getData());

        $this->assertSame($this->getJson(), (string) $event);
    }

    protected function getData()
    {
        return json_decode($this->getJson());
    }

    protected function getJson()
    {
        return '{"id":"EVTESTXDTY7F4S","created_at":"2015-11-30T22:20:07.648Z","resource_type":"mandates","action":"created","links":{"mandate":"index_ID_123"},"details":{"origin":"api","cause":"mandate_created","description":"Mandate created via the API."},"metadata":{}}';
    }
}
