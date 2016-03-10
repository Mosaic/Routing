<?php

namespace Mosaic\Routing\Providers;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Routing\Adapters\FastRoute\RouteDispatcher;
use Mosaic\Routing\Adapters\Router;
use Mosaic\Routing\Dispatchers\DispatchClosure;
use Mosaic\Routing\Dispatchers\DispatchController;
use Mosaic\Routing\Dispatchers\DispatcherChain;
use Mosaic\Routing\MethodParameterResolver;
use Mosaic\Routing\RouteDispatcher as RouteDispatcherInterface;
use Mosaic\Routing\RouteLoader;
use Mosaic\Routing\Router as RouterInterface;

class FastRouteProvider implements DefinitionProviderInterface
{
    /**
     * @var RouteLoader
     */
    private $loader;

    /**
     * @param RouteLoader $loader
     */
    public function __construct(RouteLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @return array|Definition[]
     */
    public function getDefinitions() : array
    {
        return [
            RouteDispatcherInterface::class => function ($container) {

                $method = new MethodParameterResolver($container);

                return new RouteDispatcher(
                    new DispatcherChain(
                        new DispatchClosure($method),
                        new DispatchController($container, $method)
                    ),
                    $container->make(RouterInterface::class)->all()
                );
            },
            RouterInterface::class          => $this->loader->loadRoutes(new Router)
        ];
    }
}
