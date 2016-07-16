<?php

namespace Mosaic\Routing;

use ReflectionFunctionAbstract;

class MethodParameterResolver
{
    /**
     * @var callable
     */
    private $resolver;

    /**
     * @param callable|null $resolver
     */
    public function __construct(callable $resolver = null)
    {
        $this->resolver = $resolver ?: function ($class) {
            return new $class;
        };
    }

    /**
     * @param ReflectionFunctionAbstract $reflector
     * @param array                      $parameters
     *
     * @return mixed
     */
    public function resolve(ReflectionFunctionAbstract $reflector, array $parameters = []) : array
    {
        $reflected = $reflector->getParameters();

        $resolved = [];
        foreach ($reflected as $param) {
            if ($class = $param->getClass()) {
                $resolver               = $this->resolver;
                $resolved[$param->name] = $resolver($class->name);
            } elseif (isset($parameters[$param->name])) {
                $resolved[$param->name] = $parameters[$param->name];
            }
        }

        return $resolved;
    }
}
