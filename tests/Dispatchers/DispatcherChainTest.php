<?php

namespace Mosaic\Routing\Tests\Dispatchers;

use Mosaic\Routing\Dispatchers\Dispatcher;
use Mosaic\Routing\Dispatchers\DispatcherChain;
use Mosaic\Routing\Route;

class DispatcherChainTest extends \PHPUnit_Framework_TestCase
{
    public function test_can_chain_single_dispatcher()
    {
        $chain = new DispatcherChain(
            $dispatcher = \Mockery::mock(Dispatcher::class)
        );

        $route = \Mockery::mock(Route::class);
        $next  = function () {
            return 'next';
        };

        $dispatcher->shouldReceive('dispatch')->once()->with($route, $next)->andReturn('response');

        $this->assertEquals('response', $chain->dispatch($route, $next));
    }

    public function test_can_chain_multiple_dispatchers()
    {
        $chain = new DispatcherChain(
            $dispatcher = \Mockery::mock(Dispatcher::class),
            $dispatcher2 = \Mockery::mock(Dispatcher::class)
        );

        $route = \Mockery::mock(Route::class);
        $next  = function ($response) {
            return $response;
        };

        $dispatcher->shouldReceive('dispatch')->once()->with($route, $next)->andReturn($next($route));
        $dispatcher2->shouldReceive('dispatch')->once()->with($route, $next)->andReturn('response');

        $this->assertEquals('response', $chain->dispatch($route, $next));
    }

    public function test_can_chain_multiple_dispatchers_and_end_early()
    {
        $chain = new DispatcherChain(
            $dispatcher = \Mockery::mock(Dispatcher::class),
            $dispatcher2 = \Mockery::mock(Dispatcher::class)
        );

        $route = \Mockery::mock(Route::class);
        $next  = function ($response) {
            return $response;
        };

        $dispatcher->shouldReceive('dispatch')->once()->with($route, $next)->andReturn($next($route));
        $dispatcher2->shouldReceive('dispatch')->once()->with($route, $next)->andReturn('response');

        $this->assertEquals('response', $chain->dispatch($route, $next));
    }

    public function test_returns_empty_when_no_dispatcher_can_handled_the_route()
    {
        $chain = new DispatcherChain(
            $dispatcher = \Mockery::mock(Dispatcher::class),
            $dispatcher2 = \Mockery::mock(Dispatcher::class)
        );

        $route = \Mockery::mock(Route::class);
        $next  = function ($response) {
            return $response;
        };

        $dispatcher->shouldReceive('dispatch')->once()->with($route, $next)->andReturn($next($route));
        $dispatcher2->shouldReceive('dispatch')->once()->with($route, $next)->andReturn($next($route));

        $this->assertNull($chain->dispatch($route, $next));
    }
}
