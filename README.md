Slim NewRelic
=============

Newrelic implementation for slim framework

Install
-------

```bash
composer require nicolas-cajelli/slim-newrelic
```

Setup
-----

```php

$config['responseHandlers'] = function(ContainerInterface $c) {
    return [
        $c->get(BadRequestJsonResponseHandler::class),
        $c->get(NewrelicResponseHandler::class),
    ];
};

$app->add(NewRelicTransactionMiddleware::class);

```

Configure (optional)
--------------------

If you want to define your own appName + licenseKey:

```php
$config['settings']  = [
    // ...
    'newRelic' => [
        'licenseKey' => 'your-license',
        'appName' => 'your-app'
    ]
    // ...
];
```

Advanced
--------

- Provide your own implementations for naming and/or decorators
```php
$config[NewRelicTransactionMiddleware::class] = function(ContainerInterface $c) {
    $middleware = new NewRelicTransactionMiddleware($c);
    $middleware->addTransactionDecorator(CustomDecorator::class);
    $middleware->setTransactionNaming(CustomNamingPolicy::class);
    return $middleware;
};
```