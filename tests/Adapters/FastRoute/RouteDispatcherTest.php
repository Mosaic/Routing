<?php

namespace Mosaic\Routing\Tests\Adapters\FastRoute;

use Mosaic\Contracts\Http\Request;
use Mosaic\Exceptions\MethodNotAllowedException;
use Mosaic\Exceptions\NotFoundHttpException;
use Mosaic\Routing\Adapters\FastRoute\RouteDispatcher;
use Mosaic\Routing\Route;
use Mosaic\Routing\RouteCollection;

class RouteDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RouteDispatcher
     */
    private $dispatcher;

    public function setUp()
    {
        $this->dispatcher = new RouteDispatcher();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    public function test_can_dispatch_an_existing_get_route()
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('method')->once()->andReturn('GET');
        $request->shouldReceive('path')->once()->andReturn('/');

        $collection = new RouteCollection();
        $collection->add($givenRoute = new Route(['GET'], '/', 'HomeController@index'));

        $route = $this->dispatcher->dispatch($request, $collection);

        $this->assertEquals($givenRoute, $route);
    }

    public function test_can_dispatch_an_existing_post_route()
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('method')->once()->andReturn('POST');
        $request->shouldReceive('path')->once()->andReturn('/');

        $collection = new RouteCollection();
        $collection->add($givenRoute = new Route(['POST'], '/', 'HomeController@index'));

        $route = $this->dispatcher->dispatch($request, $collection);

        $this->assertEquals($givenRoute, $route);
    }

    public function test_cannot_dispatch_post_route_as_get()
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('method')->once()->andReturn('POST');
        $request->shouldReceive('path')->once()->andReturn('/');

        $collection = new RouteCollection();
        $collection->add($givenRoute = new Route(['GET'], '/', 'HomeController@index'));

        $this->setExpectedException(MethodNotAllowedException::class, 'Method [GET, HEAD] is not allowed');
        $this->dispatcher->dispatch($request, $collection);
    }

    public function test_cannot_dispatch_non_existing_route()
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('method')->once()->andReturn('POST');
        $request->shouldReceive('path')->once()->andReturn('/');

        $this->setExpectedException(NotFoundHttpException::class);
        $this->dispatcher->dispatch($request, new RouteCollection());
    }
}
