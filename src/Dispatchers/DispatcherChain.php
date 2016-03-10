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
        return array_reduce($this->dispatchers, function ($key, $dispatcher) use ($route, $next) {
            return $dispatcher->dispatch($route, $next);
        });
    }
}
