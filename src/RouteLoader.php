<?php

namespace Mosaic\Routing;

interface RouteLoader
{
    /**
     * @param Router $router
     *
     * @return Router
     */
    public function loadRoutes(Router $router) : Router;
}
