<?php

namespace Mosaic\Routing\Loaders;

use Mosaic\Routing\RouteBinder;
use Mosaic\Routing\RouteLoader;
use Mosaic\Routing\Router;

class LoadRoutesFromBinders implements RouteLoader
{
    /**
     * @var \Mosaic\Routing\RouteBinder[]
     */
    private $binders;

    /**
     * @param \Mosaic\Routing\RouteBinder[] $binders
     */
    public function __construct(RouteBinder ...$binders)
    {
        $this->binders = $binders;
    }

    /**
     * @param Router $router
     *
     * @return Router
     */
    public function loadRoutes(Router $router) : Router
    {
        foreach ($this->binders as $binder) {
            $binder->bind($router);
        }

        return $router;
    }
}
