<?php

namespace Ockle\GoCardlessWebhook\Tests\Events;

use Mockery;
use Ockle\GoCardlessWebhook\Events\PayoutEvent;

class PayoutEventTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetId()
    {
        $event = new PayoutEvent($this->getData());

        $this->assertSame('EVTESTCSKSBWCM', $event->getId());
    }

    public function testGetCreatedAt()
    {
        $event = new PayoutEvent($this->getData());

        $this->assertSame('2015-11-30 22:51:54', $event->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    public function testActionIs()
    {
        $event = new PayoutEvent($this->getData());

        $this->assertTrue($event->actionIs(PayoutEvent::ACTION_PAID));
    }

    public function testGetPayoutId()
    {
        $event = new PayoutEvent($this->getData());

        $this->assertSame('index_ID_123', $event->getPayoutId());
    }

    public function testGetOrigin()
    {
        $event = new PayoutEvent($this->getData());

        $this->assertSame('gocardless', $event->getOrigin());
    }

    public function testGetCause()
    {
        $event = new PayoutEvent($this->getData());

        $this->assertSame('payout_paid', $event->getCause());
    }

    public function testGetDescription()
    {
        $event = new PayoutEvent($this->getData());

        $this->assertSame('GoCardless has transferred the payout to the creditor\'s bank account.', $event->getDescription());
    }

    public function testGetMetadata()
    {
        $event = new PayoutEvent($this->getData());

        $this->assertInternalType('object', $event->getMetadata());
    }

    public function testToString()
    {
        $event = new PayoutEvent($this->getData());

        $this->assertSame($this->getJson(), (string) $event);
    }

    protected function getData()
    {
        return json_decode($this->getJson());
    }

    protected function getJson()
    {
        return '{"id":"EVTESTCSKSBWCM","created_at":"2015-11-30T22:51:54.875Z","resource_type":"payouts","action":"paid","links":{"payout":"index_ID_123"},"details":{"origin":"gocardless","cause":"payout_paid","description":"GoCardless has transferred the payout to the creditor\'s bank account."},"metadata":{}}';
    }
}
