<?php

namespace Mosaic\Routing\Tests\Adapters\FastRoute;

use Mockery\Mock;
use Mosaic\Routing\Adapters\FastRoute\RouteDispatcher;
use Mosaic\Routing\Dispatchers\Dispatcher;
use Mosaic\Routing\Exceptions\MethodNotAllowedException;
use Mosaic\Routing\Exceptions\NotFoundHttpException;
use Mosaic\Routing\Route;
use Mosaic\Routing\RouteCollection;
use Psr\Http\Message\ServerRequestInterface;

class RouteDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mock
     */
    protected $request;

    /**
     * @var Mock
     */
    protected $chain;

    public function setUp()
    {
        $this->chain   = \Mockery::mock(Dispatcher::class);
        $this->request = \Mockery::mock(ServerRequestInterface::class);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    public function test_can_dispatch_an_existing_get_route()
    {
        $this->request->shouldReceive('getMethod')->once()->andReturn('GET');
        $this->request->shouldReceive('getUri')->once()->andReturn($this->request);
        $this->request->shouldReceive('getPath')->once()->andReturn('/');

        $collection = new RouteCollection();
        $collection->add(new Route(['GET'], '/', 'HomeController@index'));

        $this->chain->shouldReceive('dispatch')->once()->andReturn('response');

        $dispatcher = new RouteDispatcher($this->chain, $collection);

        $response = $dispatcher->dispatch($this->request);

        $this->assertEquals('response', $response);
    }

    public function test_can_dispatch_an_existing_post_route()
    {
        $this->request->shouldReceive('getMethod')->once()->andReturn('POST');
        $this->request->shouldReceive('getUri')->once()->andReturn($this->request);
        $this->request->shouldReceive('getPath')->once()->andReturn('/');

        $collection = new RouteCollection();
        $collection->add(new Route(['POST'], '/', 'HomeController@index'));

        $this->chain->shouldReceive('dispatch')->once()->andReturn('response');

        $dispatcher = new RouteDispatcher($this->chain, $collection);

        $response = $dispatcher->dispatch($this->request);

        $this->assertEquals('response', $response);
    }

    public function test_cannot_dispatch_post_route_as_get()
    {
        $this->request->shouldReceive('getMethod')->once()->andReturn('POST');
        $this->request->shouldReceive('getUri')->once()->andReturn($this->request);
        $this->request->shouldReceive('getPath')->once()->andReturn('/');

        $this->chain->shouldReceive('dispatch')->never();

        $collection = new RouteCollection();
        $collection->add(new Route(['GET'], '/', 'HomeController@index'));

        $dispatcher = new RouteDispatcher($this->chain, $collection);

        $this->expectException(MethodNotAllowedException::class);
        $this->expectExceptionMessage('Method [GET, HEAD] is not allowed');
        $dispatcher->dispatch($this->request);
    }

    public function test_cannot_dispatch_non_existing_route()
    {
        $this->request->shouldReceive('getMethod')->once()->andReturn('POST');
        $this->request->shouldReceive('getUri')->once()->andReturn($this->request);
        $this->request->shouldReceive('getPath')->once()->andReturn('/');

        $this->chain->shouldReceive('dispatch')->never();

        $dispatcher = new RouteDispatcher($this->chain, new RouteCollection);

        $this->expectException(NotFoundHttpException::class);
        $dispatcher->dispatch($this->request);
    }
}
