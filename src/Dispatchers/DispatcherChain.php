<?php

namespace Mosaic\Routing\Dispatchers;

use Mosaic\Routing\Route;

class DispatcherChain implements Dispatcher
{
    /**
     * @var Dispatcher[]
     */
    private $dispatchers;

    /**
     * @param $dispatchers
     */
    public function __construct(Dispatcher ...$dispatchers)
    {
        $this->dispatchers = $dispatchers;
    }

    /**
     * @param  Route    $route
     * @param  callable $next
     * @return mixed
     */
    public function dispatch(Route $route, callable $next)
    {
        foreach ($this->dispatchers as $dispatcher) {
            $response = $dispatcher->dispatch($route, $next);

            if (!$response instanceof Route) {
                return $response;
            }
        }
    }
}
