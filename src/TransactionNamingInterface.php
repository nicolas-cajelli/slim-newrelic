<?php

namespace Slim\NewRelic;


use Psr\Http\Message\ServerRequestInterface;

/**
 * Define a naming policy for all transactions
 *
 */
interface TransactionNamingInterface
{
    public function getName(ServerRequestInterface $request) : string;
}