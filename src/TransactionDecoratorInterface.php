<?php

namespace Slim\NewRelic;

use Psr\Http\Message\ServerRequestInterface;

interface TransactionDecoratorInterface
{
    function decorate(ServerRequestInterface $request) : TransactionDecoratorInterface;
}
