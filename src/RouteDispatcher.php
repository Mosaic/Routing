<?php

namespace Mosaic\Routing;

use Mosaic\Http\Request;

interface RouteDispatcher
{
    /**
     * Dispatch the request
     *
     * @param Request         $request
     * @param RouteCollection $collection
     *
     * @return mixed
     */
    public function dispatch(Request $request, RouteCollection $collection);
}
