<?php

namespace Mosaic\Routing\Dispatchers;

use Mosaic\Routing\Route;

interface Dispatcher
{
    /**
     * @param  Route    $route
     * @param  callable $next
     * @return mixed
     */
    public function dispatch(Route $route, callable $next);
}
