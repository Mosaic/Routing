<?php

namespace Mosaic\Routing;

interface RouteBinder
{
    /**
     * Bind routes to router
     *
     * @param Router $router
     */
    public function bind(Router $router);
}
