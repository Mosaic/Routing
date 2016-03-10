<?php

namespace Mosaic\Routing;

use Psr\Http\Message\ServerRequestInterface;

interface RouteDispatcher
{
    /**
     * @param  ServerRequestInterface $request
     * @return mixed
     */
    public function dispatch(ServerRequestInterface $request);
}
