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

        $this->assertSame('EVTESTHMQYHZ9V', $event->getId());
    }

    public function testGetCreatedAt()
    {
        $event = new MandateEvent($this->getData());

        $this->assertSame('2015-12-05 16:51:36', $event->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    public function testActionIs()
    {
        $event = new MandateEvent($this->getData());

        $this->assertTrue($event->actionIs(MandateEvent::ACTION_TRANSFERRED));
    }

    public function testGetMandateId()
    {
        $event = new MandateEvent($this->getData());

        $this->assertSame('index_ID_123', $event->getMandateId());
    }

    public function testGetOrigin()
    {
        $event = new MandateEvent($this->getData());

        $this->assertSame('bank', $event->getOrigin());
    }

    public function testGetCause()
    {
        $event = new MandateEvent($this->getData());

        $this->assertSame('bank_account_transferred', $event->getCause());
    }

    public function testGetDescription()
    {
        $event = new MandateEvent($this->getData());

        $this->assertSame('The customer\'s bank account was transferred to a different bank or building society.', $event->getDescription());
    }

    public function testGetScheme()
    {
        $data = $this->getData();

        $event = new MandateEvent($data);

        $this->assertSame('bacs', $event->getScheme());

        unset($data->details->scheme);

        $event = new MandateEvent($data);

        $this->assertNull($event->getScheme());
    }

    public function testGetReasonCode()
    {
        $data = $this->getData();

        $event = new MandateEvent($data);

        $this->assertSame('ADDACS-3', $event->getReasonCode());

        unset($data->details->reason_code);

        $event = new MandateEvent($data);

        $this->assertNull($event->getReasonCode());
    }

    public function testGetNewCustomerBankAccount()
    {
        $data = $this->getData();

        $event = new MandateEvent($data);

        $this->assertSame('New bank', $event->getNewCustomerBankAccount());

        unset($data->links->new_customer_bank_account);

        $event = new MandateEvent($data);

        $this->assertNull($event->getNewCustomerBankAccount());
    }

    public function testGetPreviousCustomerBankAccount()
    {
        $data = $this->getData();

        $event = new MandateEvent($data);

        $this->assertSame('Previous bank', $event->getPreviousCustomerBankAccount());

        unset($data->links->previous_customer_bank_account);

        $event = new MandateEvent($data);

        $this->assertNull($event->getPreviousCustomerBankAccount());
    }

    public function testGetOrganisation()
    {
        $data = $this->getData();

        $event = new MandateEvent($data);

        $this->assertSame('Test organisation', $event->getOrganisation());

        unset($data->links->organisation);

        $event = new MandateEvent($data);

        $this->assertNull($event->getOrganisation());
    }

    public function testGetParent()
    {
        $data = $this->getData();

        $event = new MandateEvent($data);

        $this->assertSame('TEST_PARENT', $event->getParent());

        unset($data->links->parent_event);

        $event = new MandateEvent($data);

        $this->assertNull($event->getParent());
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
        return '{"id":"EVTESTHMQYHZ9V","created_at":"2015-12-05T16:51:36.858Z","resource_type":"mandates","action":"transferred","links":{"mandate":"index_ID_123","new_customer_bank_account":"New bank","previous_customer_bank_account":"Previous bank","organisation":"Test organisation","parent_event":"TEST_PARENT"},"details":{"origin":"bank","cause":"bank_account_transferred","scheme":"bacs","reason_code":"ADDACS-3","description":"The customer\'s bank account was transferred to a different bank or building society."},"metadata":{}}';
    }
}
