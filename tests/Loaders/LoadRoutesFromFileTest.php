<?php

namespace Mosaic\Routing\Tests\Loaders;

use InvalidArgumentException;
use Mockery\Mock;
use Mosaic\Routing\Loaders\LoadRoutesFromFile;
use Mosaic\Routing\Router;
use Mosaic\Routing\Tests\fixtures\routes\StubRouteBinder;
use PHPUnit_Framework_TestCase;

class LoadRoutesFromFileTest extends PHPUnit_Framework_TestCase
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
     * @var LoadRoutesFromFile
     */
    private $loader;

    public function setUp()
    {
        $this->loader = new LoadRoutesFromFile(
            __DIR__ . '/../fixtures/routes/routes.php'
        );

        $this->router = \Mockery::mock(Router::class);
    }

    public function test_it_binds_routes_using_the_routes_file()
    {
        $router = \Mockery::mock(Router::class);
        $router->shouldReceive('get')->with('/', 'Controller@method')->once();

        $this->assertEquals($router, $this->loader->loadRoutes($router));
    }

    public function test_it_requires_all_given_files_to_exist()
    {
        $this->loader = new LoadRoutesFromFile(
            __DIR__ . '/../fixtures/routes/routes2.php'
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Route file does not exist at [' . __DIR__ . '/../fixtures/routes/routes2.php]');

        $this->loader->loadRoutes($this->router);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
