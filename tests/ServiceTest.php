<?php

namespace Ockle\GoCardlessWebhook\Tests;

use Mockery;
use Ockle\GoCardlessWebhook\Events\MandateEvent;
use Ockle\GoCardlessWebhook\Events\PaymentEvent;
use Ockle\GoCardlessWebhook\Events\PayoutEvent;
use Ockle\GoCardlessWebhook\Events\RefundEvent;
use Ockle\GoCardlessWebhook\Events\SubscriptionEvent;
use Ockle\GoCardlessWebhook\Service;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testProcessBadJson()
    {
        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);

        $service = new Service('1234', $eventDispatcher);

        $return = $service->process('49c3b42fa0338a377b88e1979bf8e421c3f38078e5878c30febdd64ce203d804', '{Foo Bar');

        $responseStatus = $service->getResponseStatus();

        $this->assertFalse($return);
        $this->assertSame(Service::RESPONSE_BAD_REQUEST, $responseStatus);
    }

    public function testProcessIncorrectSignature()
    {
        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);

        $service = new Service('1234', $eventDispatcher);

        $return = $service->process('Incorrect', $this->getPostBody());

        $responseStatus = $service->getResponseStatus();

        $this->assertFalse($return);
        $this->assertSame(Service::RESPONSE_INVALID_SIGNATURE, $responseStatus);
    }

    public function testProcessSuccess()
    {
        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Service::EVENT_MANDATE, Mockery::type(MandateEvent::class));
        $eventDispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Service::EVENT_PAYMENT, Mockery::type(PaymentEvent::class));
        $eventDispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Service::EVENT_PAYOUT, Mockery::type(PayoutEvent::class));
        $eventDispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Service::EVENT_SUBSCRIPTION, Mockery::type(SubscriptionEvent::class));
        $eventDispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Service::EVENT_REFUND, Mockery::type(RefundEvent::class));

        $service = new Service('1234', $eventDispatcher);

        $return = $service->process('a354873c752a92bb6c397a5d53faad014fad679b6c358d0e122c4703a694579b', $this->getPostBody());

        $responseStatus = $service->getResponseStatus();

        $this->assertTrue($return);
        $this->assertSame(Service::RESPONSE_OK, $responseStatus);
    }

    public function testOnPayment()
    {
        $listener = function () {};

        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('addListener')
            ->once()
            ->with(Service::EVENT_PAYMENT, $listener);

        $service = new Service('1234', $eventDispatcher);

        $return = $service->onPayment($listener);

        $this->assertSame($service, $return);
    }

    public function testOnMandate()
    {
        $listener = function () {};

        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('addListener')
            ->once()
            ->with(Service::EVENT_MANDATE, $listener);

        $service = new Service('1234', $eventDispatcher);

        $return = $service->onMandate($listener);

        $this->assertSame($service, $return);
    }

    public function testOnPayout()
    {
        $listener = function () {};

        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('addListener')
            ->once()
            ->with(Service::EVENT_PAYOUT, $listener);

        $service = new Service('1234', $eventDispatcher);

        $return = $service->onPayout($listener);

        $this->assertSame($service, $return);
    }

    public function testOnSubscription()
    {
        $listener = function () {};

        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('addListener')
            ->once()
            ->with(Service::EVENT_SUBSCRIPTION, $listener);

        $service = new Service('1234', $eventDispatcher);

        $return = $service->onSubscription($listener);

        $this->assertSame($service, $return);
    }

    public function testOnRefund()
    {
        $listener = function () {};

        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('addListener')
            ->once()
            ->with(Service::EVENT_REFUND, $listener);

        $service = new Service('1234', $eventDispatcher);

        $return = $service->onRefund($listener);

        $this->assertSame($service, $return);
    }

    private function getPostBody()
    {
        return '{"events":[{"id":"EVTESTXDTY7F4S","created_at":"2015-11-30T22:20:07.648Z","resource_type":"mandates","action":"created","links":{"mandate":"index_ID_123"},"details":{"origin":"api","cause":"mandate_created","description":"Mandate created via the API."},"metadata":{}},{"id":"EVTESTHXHH665M","created_at":"2015-11-30T22:51:00.463Z","resource_type":"payments","action":"created","links":{"payment":"index_ID_123"},"details":{"origin":"api","cause":"payment_created","description":"Payment created via the API."},"metadata":{}},{"id":"EVTESTCSKSBWCM","created_at":"2015-11-30T22:51:54.875Z","resource_type":"payouts","action":"paid","links":{"payout":"index_ID_123"},"details":{"origin":"gocardless","cause":"payout_paid","description":"GoCardless has transferred the payout to the creditor\'s bank account."},"metadata":{}},{"id":"EVTESTG53JCVBS","created_at":"2015-11-30T22:52:44.431Z","resource_type":"refunds","action":"created","links":{"refund":"index_ID_123"},"details":{"origin":"api","cause":"payment_refunded","description":"The refund has been created, and will be submitted in the next batch."},"metadata":{}},{"id":"EVTEST8MC38NRB","created_at":"2015-11-30T22:53:39.875Z","resource_type":"subscriptions","action":"created","links":{"payment":"payment_ID_123","subscription":"index_ID_123"},"details":{"origin":"api","cause":"subscription_created","description":"Subscription created via the API."},"metadata":{}}]}';
    }
}