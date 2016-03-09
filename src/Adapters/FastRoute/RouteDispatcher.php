<?php

namespace Mosaic\Routing\Adapters\FastRoute;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Mosaic\Http\Exceptions\MethodNotAllowedException;
use Mosaic\Http\Exceptions\NotFoundHttpException;
use Mosaic\Http\Request;
use Mosaic\Routing\Route;
use Mosaic\Routing\RouteCollection;
use Mosaic\Routing\RouteDispatcher as RouteDispatcherContract;
use Psr\Http\Message\ServerRequestInterface;

class RouteDispatcher implements RouteDispatcherContract
{
    /**
     * Dispatch the request
     *
     * @param ServerRequestInterface $request
     * @param RouteCollection        $collection
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundHttpException
     * @return Route
     */
    public function dispatch(ServerRequestInterface $request, RouteCollection $collection)
    {
        $method = $request->getMethod();
        $uri    = $request->getUri()->getPath();

        $routeInfo = $this->createDispatcher($collection)->dispatch($method, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new NotFoundHttpException;

            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException($routeInfo[1]);

            case Dispatcher::FOUND:
                $route = $routeInfo[1];
                $route->bind($routeInfo[2]);
        }

        return $route;
    }

    /**
     * @param RouteCollection $collection
     *
     * @return Dispatcher
     */
    private function createDispatcher(RouteCollection $collection)
    {
        return \FastRoute\simpleDispatcher(function (RouteCollector $collector) use ($collection) {
            foreach ($collection as $route) {
                $collector->addRoute($route->methods(), $route->uri(), $route);
            }
        });
    }
}
