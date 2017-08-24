<?php

namespace Slim\NewRelic;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @inheritdoc
 *
 * Default naming policy. "{method} - {path}"
 */
class DefaultTransactionNaming implements TransactionNamingInterface
{
    public function __construct(array $settings)
    {
        if (
            ! isset($settings['determineRouteBeforeAppMiddleware'])
            || ! $settings['determineRouteBeforeAppMiddleware']
        ) {
            throw new \RuntimeException(
                'determineRouteBeforeAppMiddleware must be enabled in order to be able to track transactions.'
            );
        }
    
    }
    
    
    public function getName(ServerRequestInterface $request) : string
    {
        $route = $request->getAttribute('route');
    
        $method = $request->getMethod();
        $path = $route->getPattern();
        $transactionName = sprintf('%s - %s', $method, $path);
        return $transactionName;
    }
}
