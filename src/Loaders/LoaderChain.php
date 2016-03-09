<?php

namespace Mosaic\Routing\Loaders;

use Mosaic\Routing\RouteLoader;
use Mosaic\Routing\Router;

class LoaderChain implements RouteLoader
{
    /**
     * @var RouteLoader[]
     */
    private $loaders;

    /**
     * LoaderChain constructor.
     * @param array $loaders
     */
    public function __construct(array $loaders = [])
    {
        $this->loaders = $loaders;
    }

    /**
     * @param Router $router
     *
     * @return Router
     */
    public function loadRoutes(Router $router) : Router
    {
        foreach ($this->loaders as $loader) {
            $loader->loadRoutes($router);
        }

        return $router;
    }

    /**
     * @param  RouteLoader $loader
     * @return $this
     */
    public function add(RouteLoader $loader)
    {
        $this->loaders[] = $loader;

        return $this;
    }
}
