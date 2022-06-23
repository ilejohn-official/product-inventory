<?php

namespace App\Routing;

use App\Interface\RouteCollectionInterface;

class RouteCollection implements RouteCollectionInterface
{
    private $acceptedRequestMethods = ['GET', 'POST', 'PUT', 'DELETE'];
    private $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    public function getRoutes() : array
    {
        return $this->routes;
    }

     /**
     * SET route
     *
     * @param string $httpMethod
     * @param string $uri
     * @param callable $controllerMethod
     * @return void
     */

    private function registerRoute(string $httpMethod, string $uri, callable $controllerMethod) : void
    {
        if ($this->validRouteParams($httpMethod, $uri, $controllerMethod)) {
            $this->storeRoute($httpMethod, $uri, $controllerMethod);
        }
    }

     /**
     * store route
     *
     * @param string $httpMethod
     * @param string $uri
     * @param callable $controllerMethod
     * @return void
     */
    private function storeRoute($httpMethod, $uri, $controllerMethod) : void
    {
        array_push($this->routes, [
            'path' => $uri,
            'httpMethod' => $httpMethod ,
            'callable' => $controllerMethod
        ]);
    }

    /**
     * validate route params
     *
     * @param string $httpMethod
     * @param string $uri
     * @param callable $controllerMethod
     * @return bool
     */

    private function validRouteParams(string $httpMethod, string $uri, callable $controllerMethod) : bool
    {
        $key = array_search(true, array_map(function($item) use ($uri, $httpMethod){
            return $item['path'] === $uri && $item['httpMethod'] === $httpMethod;
         }, $this->routes)
        );

        return in_array($httpMethod, $this->acceptedRequestMethods) &&  is_callable($controllerMethod) && $key === false;
    }

    /**
     * GET route
     *
     * @param string $path
     * @param callable $controllerMethod
     * @return void
     */
    public function get(string $path, callable $controllerMethod) : void
    {
        $this->registerRoute('GET', $path, $controllerMethod);
    }

    /**
     * POST route
     *
     * @param string $path
     * @param callable $controllerMethod
     * @return void
     */
    public function post(string $path, callable $controllerMethod) : void
    {
        $this->registerRoute('POST', $path, $controllerMethod);
    }

    /**
     * PUT route
     *
     * @param string $path
     * @param callable $controllerMethod
     * @return void
     */
    public function put(string $path, callable $controllerMethod) : void
    {
        $this->registerRoute('PUT', $path, $controllerMethod);
    }

    /**
     * DELETE route
     *
     * @param string $path
     * @param callable $controllerMethod
     * @return void
     */
    public function delete(string $path, callable $controllerMethod) : void
    {
        $this->registerRoute('DELETE', $path, $controllerMethod);
    }
}
