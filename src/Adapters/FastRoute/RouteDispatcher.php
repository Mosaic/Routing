<?php

namespace Mosaic\Routing\Adapters\FastRoute;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Mosaic\Routing\Dispatchers\Dispatcher as DispatcherInterface;
use Mosaic\Routing\Exceptions\MethodNotAllowedException;
use Mosaic\Routing\Exceptions\NotFoundHttpException;
use Mosaic\Routing\RouteCollection;
use Mosaic\Routing\RouteDispatcher as RouteDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouteDispatcher implements RouteDispatcherInterface
{
    /**
     * @var RouteCollection
     */
    private $collection;

    /**
     * @var DispatcherInterface
     */
    private $dispatcher;

    /**
     * @param DispatcherInterface $dispatcher
     * @param RouteCollection     $collection
     */
    public function __construct(DispatcherInterface $dispatcher, RouteCollection $collection)
    {
        $this->collection = $collection;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Dispatch the request
     *
     * @param  ServerRequestInterface    $request
     * @throws MethodNotAllowedException
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $method = $request->getMethod();
        $uri    = $request->getUri()->getPath();

        $routeInfo = $this->createDispatcher()->dispatch($method, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new NotFoundHttpException;

            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException($routeInfo[1]);

            case Dispatcher::FOUND:
                $route = $routeInfo[1];
                $route->bind($routeInfo[2]);

                return $this->dispatcher->dispatch($route, function ($response) {
                    return $response;
                });
        }
    }

    /**
     * @return Dispatcher
     */
    private function createDispatcher()
    {
        return \FastRoute\simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->collection as $route) {
                $collector->addRoute($route->methods(), $route->uri(), $route);
            }
        });
    }
}
