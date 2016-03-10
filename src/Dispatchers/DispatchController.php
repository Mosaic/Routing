<?php

namespace Mosaic\Routing\Dispatchers;

use Mosaic\Container\Container;
use Mosaic\Http\Exceptions\NotFoundHttpException;
use Mosaic\Routing\MethodParameterResolver;
use Mosaic\Routing\Route;
use ReflectionMethod;

class DispatchController implements Dispatcher
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var MethodParameterResolver
     */
    private $resolver;

    /**
     * @param Container               $container
     * @param MethodParameterResolver $resolver
     */
    public function __construct(Container $container, MethodParameterResolver $resolver)
    {
        $this->container = $container;
        $this->resolver  = $resolver;
    }

    /**
     * Dispatch the request
     *
     * @param  Route                 $route
     * @param  callable              $next
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function dispatch(Route $route, callable $next)
    {
        $action = $route->action();

        list($class, $method) = explode('@', $action['uses']);

        if (!method_exists($instance = $this->container->make($class), $method)) {
            throw new NotFoundHttpException;
        }

        $parameters = $this->resolver->resolve(
            new ReflectionMethod($instance, $method),
            $route->parameters()
        );

        return $this->container->call([$instance, $method], $parameters);
    }
}
