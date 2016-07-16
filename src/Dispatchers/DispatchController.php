<?php

namespace Mosaic\Routing\Dispatchers;

use Mosaic\Routing\Exceptions\NotFoundHttpException;
use Mosaic\Routing\MethodParameterResolver;
use Mosaic\Routing\Route;
use ReflectionMethod;

class DispatchController implements Dispatcher
{
    /**
     * @var MethodParameterResolver
     */
    private $method;

    /**
     * @var callable
     */
    private $resolver;

    /**
     * @param MethodParameterResolver $method
     * @param callable                $resolver
     */
    public function __construct(MethodParameterResolver $method, callable $resolver = null)
    {
        $this->method  = $method;
        $this->resolver = $resolver ?: function($class, $method = null, array $parameters = []) {
            if(is_null($method)) {
                return new $class;
            }

            return call_user_func_array([$class, $method], $parameters);
        };
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

        $resolver = $this->resolver;

        if (!method_exists($instance = $resolver($class), $method)) {
            throw new NotFoundHttpException;
        }

        $parameters = $this->method->resolve(
            new ReflectionMethod($instance, $method),
            $route->parameters()
        );

        return $resolver($instance, $method, $parameters);
    }
}
