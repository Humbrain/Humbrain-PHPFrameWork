<?php

namespace Steodec\SteoFrameWork\Routers;

class Router {
    private string $_url;
    private array  $_routes      = [];
    private array  $_namedRoutes = [];

    /**
     * @param string $_url
     */
    public function __construct(string $_url) { $this->_url = $_url; }

    public function add(string $path, string $callable, string $name, string $method): Route {
        $route                     = new Route($path, $callable);
        $this->_routes[$method][]  = $route;
        $this->_namedRoutes[$name] = $route;
        return $route;
    }

    /**
     * @throws RouterException
     */
    public function run(): mixed {
        if (!isset($this->_routes[$_SERVER['REQUEST_METHOD']])) {
            throw new RouterException('REQUEST_METHOD does not exist');
        }
        foreach ($this->_routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route instanceof Route)
                if ($route->match($this->_url)) {
                    return $route->call();
                }
        }
        header("HTTP/1.0 404 Not Found");
        return FALSE;
    }

    /**
     * @throws RouterException
     */
    public function url(string $name, array $params = []) {
        if (!isset($this->namedRoutes[$name])) {
            throw new RouterException('No route matches this name');
        }
        return $this->_namedRoutes[$name]->getUrl($params);
    }


}