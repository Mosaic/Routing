<?php

namespace Mosaic\Tests\Definitions;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Definitions\FastRouteDefinition;
use Mosaic\Routing\RouteDispatcher;
use Mosaic\Routing\Router;

class FastRouteDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function getDefinition() : DefinitionProviderInterface
    {
        return new FastRouteDefinition();
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
