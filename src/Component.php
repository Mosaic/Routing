<?php

namespace Mosaic\Routing;

use Mosaic\Common\Components\AbstractComponent;
use Mosaic\Routing\Loaders\LoadRoutesFromBinders;
use Mosaic\Routing\Loaders\LoadRoutesFromFile;
use Mosaic\Routing\Providers\FastRouteProvider;

/**
 * @method static $this fastRoute(RouteLoader $loader)
 */
final class Component extends AbstractComponent
{
    /**
     * @var RouteLoader
     */
    private $loader;

    /**
     * @param string      $implementation
     * @param RouteLoader $loader
     */
    protected function __construct(string $implementation, RouteLoader $loader = null)
    {
        $this->loader = $loader;
        parent::__construct($implementation);
    }

    /**
     * @return array
     */
    public function resolveFastRoute()
    {
        return [
            new FastRouteProvider($this->loader)
        ];
    }

    /**
     * @param \string[] ...$paths
     * @return $this
     */
    public function from(string ...$paths)
    {
        $this->loader = new LoadRoutesFromFile(...$paths);

        return $this;
    }

    /**
     * @param RouteBinder[] ...$binders
     * @return $this
     */
    public function bind(RouteBinder ...$binders)
    {
        $this->loader = new LoadRoutesFromBinders(...$binders);

        return $this;
    }

    /**
     * @param  callable $callback
     * @return array
     */
    public function resolveCustom(callable $callback) : array
    {
        return $callback($this->loader);
    }
}
