<?php

namespace Mosaic\Routing\Loaders;

use InvalidArgumentException;
use Mosaic\Application;
use Mosaic\Config\Config;
use Mosaic\Container\Container;
use Mosaic\Routing\RouteLoader;
use Mosaic\Routing\Router;

class LoadRoutesFromBinders implements RouteLoader
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Container
     */
    private $container;

    /**
     * LoadRoutesFromBinders constructor.
     *
     * @param Application $app
     * @param Container   $container
     * @param Config      $config
     */
    public function __construct(Application $app, Container $container, Config $config)
    {
        $this->app       = $app;
        $this->config    = $config;
        $this->container = $container;
    }

    /**
     * @param Router $router
     *
     * @return mixed
     */
    public function loadRoutes(Router $router)
    {
        $binders = $this->config->get($this->app->getContext() . '.routes', []);

        foreach ($binders as $binder) {
            if (!class_exists($binder)) {
                throw new InvalidArgumentException('RouteBinder [' . $binder . '] does not exist');
            }

            $this->container->make($binder)->bind($router);
        }
    }
}
