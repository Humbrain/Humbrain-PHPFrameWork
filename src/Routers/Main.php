<?php

namespace Steodec\SteoFrameWork\Routers;

use Exception;
use HaydenPierce\ClassFinder\ClassFinder;
use ReflectionClass;
use ReflectionException;
use Steodec\SteoFrameWork\Routers\Attributes\HTTPMethods;
use Steodec\SteoFrameWork\Routers\Attributes\RouteAttribute;

class Main {
    /**
     * @var string
     */
    private string $namespace;
    /**
     * @var callable|null
     */
    private mixed $middleware;

    /**
     * @param string $namespace
     * @param callable|null $middleware
     */
    public function __construct(string $namespace, ?callable $middleware = NULL) {
        $this->namespace  = $namespace;
        $this->middleware = $middleware;
    }

    /**
     * @throws RouterException
     * @throws ReflectionException
     * @throws Exception
     */
    public function run(): void {
        $routes = $this->getRoute();
        $router = new Router($_GET['url']);
        foreach ($routes as $route) {
            if (empty($route->getIsGranted())):
                $router->add($route->getPath(), $route->getCallable(), $route->getName(), $route->getMethod());
            else:
                if ($this->getMiddleware() == NULL or $this->getMiddleware()($route)) {
                    $router->add($route->getPath(), $route->getCallable(), $route->getName(), $route->getMethod());
                }
            endif;
        }
        $router->run();
    }

    /**
     * @throws ReflectionException
     */
    private function registerController(string $controller): array {
        $class      = new ReflectionClass($controller);
        $routeArray = [];
        foreach ($class->getMethods() as $method) {
            $router = $method->getAttributes(RouteAttribute::class, \ReflectionAttribute::IS_INSTANCEOF);
            if (empty($router)) continue;
            foreach ($router as $r) {
                $newRoute = $r->newInstance();
                if ($newRoute instanceof RouteAttribute):
                    $array       = explode('\\', strtolower($method->class));
                    $class       = end($array);
                    $method_name = strtolower($method->name);
                    if ($method_name == 'index' && empty($newRoute->getPath()))
                        $newRoute->setPath("/{$class}/");
                    if (empty($newRoute->getPath())) $newRoute->setPath("/{$class}/{$method_name}");
                    if (empty($newRoute->getMethod())) $newRoute->setMethod(HTTPMethods::GET);
                    $newRoute->setCallable($method->class . "#" . $method->name);
                    $routeArray[] = $newRoute;
                endif;
            }
        }
        return $routeArray;
    }

    /**
     * @return array
     * @throws Exception
     * @throws ReflectionException
     */
    public function getRoute(): array {
        $classes = ClassFinder::getClassesInNamespace($this->getNamespace(), ClassFinder::RECURSIVE_MODE);
        $routes  = [];
        foreach ($classes as $class) {
            $routes = array_merge($routes, self::registerController($class));
        }
        return $routes;
    }

    /**
     * @return string
     */
    public function getNamespace(): string {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void {
        $this->namespace = $namespace;
    }

    /**
     * @return callable|null
     */
    public function getMiddleware(): ?callable {
        return $this->middleware;
    }

    /**
     * @param callable|null $middleware
     */
    public function setMiddleware(mixed $middleware): void {
        $this->middleware = $middleware;
    }

}
