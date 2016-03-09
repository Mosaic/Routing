<?php

namespace Mosaic\Routing\Providers;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Routing\Loaders\LoadRoutesFromFile;
use Mosaic\Routing\RouteLoader;

class RouteFileProvider implements DefinitionProviderInterface
{
    /**
     * Returns the definition to register in the container.
     *
     * Definitions must be indexed by their entry ID. For example:
     *
     *     return [
     *         'logger' => ...
     *         'mailer' => ...
     *     ];
     *
     * @return array
     */
    public function getDefinitions()
    {
        return [
            RouteLoader::class => function () {
                return new LoadRoutesFromFile;
            }
        ];
    }
}
