<?php

namespace Mosaic\Routing\Tests\Loaders;

use Mockery\Mock;
use Mosaic\Routing\Loaders\LoaderChain;
use Mosaic\Routing\RouteLoader;
use Mosaic\Routing\Router;

class LoaderChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mock
     */
    protected $router;

    public function setUp()
    {
        $this->router = \Mockery::mock(Router::class);
    }

    public function test_wont_load_routes_when_no_loaders_are_given()
    {
        $chain = new LoaderChain();
        $this->assertEquals($this->router, $chain->loadRoutes($this->router));
    }

    public function test_can_provide_loaders()
    {
        $chain = new LoaderChain([
            $loader = \Mockery::mock(RouteLoader::class)
        ]);

        $loader->shouldReceive('loadRoutes')->with($this->router)->once();

        $this->assertEquals($this->router, $chain->loadRoutes($this->router));
    }

    public function test_can_add_loaders()
    {
        $chain = new LoaderChain();
        $chain->add($loader = \Mockery::mock(RouteLoader::class));

        $loader->shouldReceive('loadRoutes')->with($this->router)->once();

        $this->assertEquals($this->router, $chain->loadRoutes($this->router));
    }

    public function test_can_add_multiple_loaders()
    {
        $chain = new LoaderChain();
        $chain->add($loader = \Mockery::mock(RouteLoader::class));
        $chain->add($loader2 = \Mockery::mock(RouteLoader::class));

        $loader->shouldReceive('loadRoutes')->with($this->router)->once();
        $loader2->shouldReceive('loadRoutes')->with($this->router)->once();

        $this->assertEquals($this->router, $chain->loadRoutes($this->router));
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
