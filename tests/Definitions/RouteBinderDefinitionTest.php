<?php

namespace Mosaic\Tests\Definitions;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Definitions\RouteBinderDefinition;
use Mosaic\Routing\RouteLoader;

class RouteBinderDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function getDefinition() : DefinitionProviderInterface
    {
        return new RouteBinderDefinition();
    }

    public function shouldDefine() : array
    {
        return [
            RouteLoader::class
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
