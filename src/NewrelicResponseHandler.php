<?php

namespace Slim\NewRelic;

use Slim\ErrorHandling\ResponseHandlerInterface;
use Slim\Http\Response;
use Throwable;

class NewrelicResponseHandler implements ResponseHandlerInterface
{
    
    /**
     */
    public function handle(Response $response, Throwable $exception)
    {
        if (extension_loaded('newrelic')) {
            newrelic_notice_error($exception->getMessage(), $exception);
        }
        return null;
    }
}
