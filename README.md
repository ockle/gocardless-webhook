# GoCardless Webhook

[![Build Status](https://travis-ci.org/ockle/gocardless-webhook.svg?branch=master)](https://travis-ci.org/ockle/gocardless-webhook)
[![Coverage Status](https://coveralls.io/repos/ockle/gocardless-webhook/badge.svg?branch=master&service=github)](https://coveralls.io/github/ockle/gocardless-webhook?branch=master)

A PHP library to aid is the processing of webhooks from GoCardless (see [API documentation](https://developer.gocardless.com/pro/2015-07-06/#webhooks-overview) for more information).

## Installation

Install the library using Composer. Add the following to your composer.json:

```json
{
    "require": {
        "ockle/gocardless-webhook": "~1.0"
    }
}
```

and then run:

```bash
composer update ockle/gocardless-webhook
```

## Usage

Instantaiate a new webhook service:

```php
// $secret is the string entered in the GoCardless admin when setting up a webhook endpoint
$webhook = new \Ockle\GoCardlessWebhook\Service($secret, new \Symfony\Component\EventDispatcher\EventDispatcher);
```

Then attach event listeners, e.g.:

```php
$webhook->onMandate(function (\Ockle\GoCardlessWebhook\Events\MandateEvent $event) use ($client) {
    // Do stuff when a mandate event occurs

    // You can check what the action is:
    if ($event->actionIs(\Ockle\GoCardlessWebhook\Events\MandateEvent::ACTION_CREATED)) {
        // Mandate has been created
    }
    
    // However, it is advised that you don't do any important business logic based off this, but
    // rather do an API call to get the current status of the mandate and act on that as the accurate
    // source of mandate information. This is due to the fact that events may be received in any order.
    
    // There are methods to get information about the event, e.g.:
    $mandateId = $event->getMandateId();
});
```

Next, process the webhook, which will trigger your event listeners as necessary:

```php
$webhook->process($webhookSignatureFromHeaders, $rawPostBody);
```

Finally, return the correct HTTP status code is your response, which can be got by calling:

```php
$webhook->getResponseStatus();
```

## License

MIT
