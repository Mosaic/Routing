<?php

namespace Mosaic\Routing\Loaders;

use InvalidArgumentException;
use Mosaic\Routing\RouteLoader;
use Mosaic\Routing\Router;

class LoadRoutesFromFile implements RouteLoader
{
    /**
     * @var string[]
     */
    private $paths;

    /**
     * @param string[] ...$paths
     */
    public function __construct(string ...$paths)
    {
        $this->paths = $paths;
    }

    /**
     * @param Router $router
     *
     * @return Router
     */
    public function loadRoutes(Router $router) : Router
    {
        foreach ($this->paths as $path) {
            if (!file_exists($path)) {
                throw new InvalidArgumentException('Route file does not exist at [' . $path . ']');
            }

            include_once $path;
        }

        return $router;
    }
}
