<?php

namespace Mosaic\Routing\Tests\fixtures\routes;

use Mosaic\Routing\RouteBinder;
use Mosaic\Routing\Router;

class StubRouteBinder implements RouteBinder
{
    /**
     * Bind routes to router
     *
     * @param Router $router
     */
    public function bind(Router $router)
    {
        $router->get('/', 'Controller@method');
    }
}
