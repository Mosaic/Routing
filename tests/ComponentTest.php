<?php

namespace Mosaic\Routing\Tests;

use Mosaic\Routing\Component;
use Mosaic\Routing\Loaders\LoaderChain;
use Mosaic\Routing\Loaders\LoadRoutesFromBinders;
use Mosaic\Routing\Loaders\LoadRoutesFromFile;
use Mosaic\Routing\Providers\FastRouteProvider;
use Mosaic\Routing\Tests\fixtures\routes\StubRouteBinder;

class ComponentTest extends \PHPUnit_Framework_TestCase
{
    public function test_can_resolve_fastroute()
    {
        $component = Component::fastRoute();

        $this->assertInstanceOf(Component::class, $component);
        $this->assertEquals('fastRoute', $component->getImplementation());
        $this->assertEquals([new FastRouteProvider(
            new LoaderChain()
        )], $component->getProviders());
    }

    public function test_can_add_binders_to_router_as_param()
    {
        $component = Component::fastRoute(
            $binder = new LoadRoutesFromBinders(new StubRouteBinder)
        );

        $this->assertInstanceOf(Component::class, $component);

        $this->assertEquals([new FastRouteProvider(
            new LoaderChain([$binder])
        )], $component->getProviders());
    }

    public function test_can_add_file_loader()
    {
        $component = Component::fastRoute()->files(
            __DIR__ . '/fixtures/routes/routes.php'
        );

        $this->assertInstanceOf(Component::class, $component);

        $this->assertEquals([new FastRouteProvider(
            new LoaderChain([new LoadRoutesFromFile(
                __DIR__ . '/fixtures/routes/routes.php'
            )])
        )], $component->getProviders());
    }

    public function test_can_add_file_loader_with_multiple_files()
    {
        $component = Component::fastRoute()->files(
            __DIR__ . '/fixtures/routes/routes.php',
            __DIR__ . '/fixtures/routes/routes2.php'
        );

        $this->assertInstanceOf(Component::class, $component);

        $this->assertEquals([new FastRouteProvider(
            new LoaderChain([new LoadRoutesFromFile(
                __DIR__ . '/fixtures/routes/routes.php',
                __DIR__ . '/fixtures/routes/routes2.php'
            )])
        )], $component->getProviders());
    }

    public function test_can_add_binders_to_router()
    {
        $component = Component::fastRoute()->binders(
            $binder = new StubRouteBinder
        );

        $this->assertInstanceOf(Component::class, $component);

        $this->assertEquals([new FastRouteProvider(
            new LoaderChain([new LoadRoutesFromBinders(
                $binder
            )])
        )], $component->getProviders());
    }

    public function test_can_add_multiple_binders_to_router()
    {
        $component = Component::fastRoute()->binders(
            $binder = new StubRouteBinder,
            $binder2 = new StubRouteBinder
        );

        $this->assertInstanceOf(Component::class, $component);

        $this->assertEquals([new FastRouteProvider(
            new LoaderChain([new LoadRoutesFromBinders($binder, $binder2)])
        )], $component->getProviders());
    }

    public function test_can_resolve_custom()
    {
        Component::extend('customRouter', function () {
            return [
                new FastRouteProvider(
                    new LoaderChain()
                )
            ];
        });

        $component = Component::customRouter();

        $this->assertInstanceOf(Component::class, $component);
        $this->assertEquals('customRouter', $component->getImplementation());
        $this->assertEquals([new FastRouteProvider(
            new LoaderChain()
        )], $component->getProviders());
    }
}
