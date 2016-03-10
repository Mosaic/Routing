<?php

namespace Mosaic\Routing\Tests\Loaders;

use Mockery\Mock;
use Mosaic\Routing\Loaders\LoadRoutesFromBinders;
use Mosaic\Routing\Router;
use Mosaic\Routing\Tests\fixtures\routes\StubRouteBinder;
use PHPUnit_Framework_TestCase;

class LoadRoutesFromBindersTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var StubRouteBinder
     */
    protected $binder;

    /**
     * @var Mock
     */
    protected $router;

    /**
     * @var LoadRoutesFromBinders
     */
    private $loader;

    public function setUp()
    {
        $this->loader = new LoadRoutesFromBinders(
            $this->binder = new StubRouteBinder()
        );

        $this->router = \Mockery::mock(Router::class);
    }

    public function test_it_binds_routes_using_all_route_binders()
    {
        $this->router->shouldReceive('get')->with('/', 'Controller@method')->once();

        $this->assertEquals($this->router, $this->loader->loadRoutes($this->router));
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
