<?php

namespace Mosaic\Routing\Tests\Dispatchers;

use Mosaic\Routing\Dispatchers\DispatchClosure;
use Mosaic\Routing\MethodParameterResolver;
use Mosaic\Routing\Route;

class DispatchClosureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MethodParameterResolver|\Mockery\Mock
     */
    private $resolver;

    /**
     * @var DispatchClosure
     */
    private $dispatcher;

    public function setUp()
    {
        $this->resolver = \Mockery::mock(MethodParameterResolver::class);
        $this->resolver->shouldReceive('resolve')->andReturn([]);

        $this->dispatcher = new DispatchClosure(
            $this->resolver
        );
    }

    public function test_can_dispatch_closure()
    {
        $route = new Route(['GET'], '/', [
            'uses' => function () {
                return 'response';
            }
        ]);

        $response = $this->dispatcher->dispatch($route, function () {
        });

        $this->assertEquals('response', $response);
    }

    public function test_will_skip_when_not_closure()
    {
        $route = new Route(['GET'], '/', [
            'uses' => 'Controller@method'
        ]);

        $response = $this->dispatcher->dispatch($route, function () {
            return 'skippedClosure-wentToNext';
        });

        $this->assertEquals('skippedClosure-wentToNext', $response);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
