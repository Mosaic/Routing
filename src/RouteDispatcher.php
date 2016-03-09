<?php

namespace Mosaic\Routing;

use Psr\Http\Message\ServerRequestInterface;

interface RouteDispatcher
{
    /**
     * Dispatch the request
     *
     * @param ServerRequestInterface $request
     * @param RouteCollection        $collection
     *
     * @return mixed
     */
    public function dispatch(ServerRequestInterface $request, RouteCollection $collection);
}
