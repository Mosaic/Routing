<?php

namespace Mosaic\Tests\Providers;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Routing\Loaders\LoadRoutesFromBinders;
use Mosaic\Routing\Providers\FastRouteProvider;
use Mosaic\Routing\RouteDispatcher;
use Mosaic\Routing\Router;
use Mosaic\Routing\Tests\fixtures\routes\StubRouteBinder;

class FastRouteProviderTest extends \PHPUnit_Framework_TestCase
{
    public function getDefinition() : DefinitionProviderInterface
    {
        return new FastRouteProvider(
            new LoadRoutesFromBinders(
                new StubRouteBinder
            )
        );
    }

    public function shouldDefine() : array
    {
        return [
            RouteDispatcher::class,
            Router::class
        ];
    }

    public function test_defines_all_required_contracts()
    {
        $definitions = $this->getDefinition()->getDefinitions();
        foreach ($this->shouldDefine() as $define) {
            $this->assertArrayHasKey($define, $definitions);
        }
    }
}
