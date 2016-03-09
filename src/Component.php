<?php

namespace Mosaic\Routing;

use Mosaic\Common\Components\AbstractComponent;
use Mosaic\Routing\Providers\FastRouteProvider;

/**
 * @method static $this fastRoute()
 */
final class Component extends AbstractComponent
{
    /**
     * @return array
     */
    public function resolveFastRoute()
    {
        return [
            new FastRouteProvider()
        ];
    }

    /**
     * @param  callable $callback
     * @return array
     */
    public function resolveCustom(callable $callback) : array
    {
        return $callback();
    }
}
