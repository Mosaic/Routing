<?php

namespace Mosaic\Routing\Tests\Dispatchers;

use Mosaic\Routing\Dispatchers\DispatchController;
use Mosaic\Routing\Exceptions\NotFoundHttpException;
use Mosaic\Routing\MethodParameterResolver;
use Mosaic\Routing\Route;

class DispatchControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MethodParameterResolver|\Mockery\Mock
     */
    private $method;

    /**
     * @var DispatchController
     */
    private $dispatcher;

    public function setUp()
    {
        $this->method  = \Mockery::mock(MethodParameterResolver::class);
        $this->method->shouldReceive('resolve')->andReturn([]);

        $this->dispatcher = new DispatchController(
            $this->method
        );
    }

    public function test_can_dispatch_controller()
    {
        $route = new Route(['GET'], '/', ['uses' => 'Mosaic\Routing\Tests\Dispatchers\ControllerStub@index']);

        $response = $this->dispatcher->dispatch($route, function () {
        });

        $this->assertEquals('response', $response);
    }

    public function test_cannot_dispatch_when_controller_does_not_exist()
    {
        $this->expectException(\Error::class);

        $route = new Route(['GET'], '/', ['uses' => 'ControllerNotExists@index']);

        $response = $this->dispatcher->dispatch($route, function () {
        });
    }

    public function test_cannot_dispatch_when_method_does_not_exist()
    {
        $this->expectException(NotFoundHttpException::class);

        $route = new Route(['GET'], '/', ['uses' => 'Mosaic\Routing\Tests\Dispatchers\ControllerStub@nonExisting']);

        $this->dispatcher->dispatch($route, function () {
        });
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}

class ControllerStub
{
    public function index()
    {
        return 'response';
    }
}
