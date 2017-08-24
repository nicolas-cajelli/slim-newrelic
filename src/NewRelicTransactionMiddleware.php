<?php
namespace Slim\NewRelic;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NewRelicTransactionMiddleware
{
    /**
     * @var TransactionDecoratorInterface[]
     */
    protected $decorators = [];
    /**
     * @var TransactionNamingInterface
     */
    protected $transactionNaming;
    
    /**
     * NewRelicTransactionMiddleware constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $settings = $container->get('settings');
        
        $this->transactionNaming = new DefaultTransactionNaming($settings);
        $this->startApplication($settings);
    }
    
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $next
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if ($this->isNewrelicEnabled()) {
            
            $route = $request->getAttribute('route');
            if ($route != null) {
                $transactionName = $this->transactionNaming->getName($request);
                newrelic_name_transaction($transactionName);
                foreach ($this->decorators as $decorator) {
                    $decorator->decorate($request);
                }
            }
        }
        return $next($request, $response);
    }
    
    /**
     * Optionally add your own decorators for the transaction (ex: track custom attributes)
     *
     * @param TransactionDecoratorInterface $decorator
     * @return NewRelicTransactionMiddleware
     */
    public function addTransactionDecorator(TransactionDecoratorInterface $decorator) : NewRelicTransactionMiddleware
    {
        $this->decorators[] = $decorator;
        return $this;
    }
    
    /**
     * Check if newrelic extension is loaded
     *
     * @return bool
     */
    private function isNewrelicEnabled(): bool
    {
        return extension_loaded('newrelic');
    }
    
    /**
     * Optionally define custom runtime properties on newrelic api
     *
     * @param array $settings
     * @return NewRelicTransactionMiddleware
     */
    protected function startApplication(array $settings) : NewRelicTransactionMiddleware
    {
        if ($this->isNewrelicEnabled() && isset($settings['newRelic'])) {
            if (isset($newRelic['licenseKey']) && isset($newRelic['appName'])) {
                newrelic_set_appname($newRelic['appName'], $newRelic['licenseKey']);
            }
        }
        return $this;
    }
    
    /**
     * @param TransactionNamingInterface $transactionNaming
     * @return NewRelicTransactionMiddleware
     */
    public function setTransactionNaming(TransactionNamingInterface $transactionNaming) : NewRelicTransactionMiddleware
    {
        $this->transactionNaming = $transactionNaming;
        return $this;
    }
}
