<?php

namespace App\Routing;

use App\Interface\RequestInterface;

class Router
{
    private $acceptedRequestMethods = ['GET', 'POST', 'PUT', 'DELETE'];
    private $routeCollection = [];

    public function __construct(private RequestInterface $request)
    {

    }

    /**
     * Process the router
     *
     * @return void
     */
    public function process()
    {
        try {
            $key = array_search(true, array_map(function($item){
                return ( 
                    $item['path'] == $this->requestPath() && 
                    $item['httpMethod'] == $this->requestMethod()
                );
             }, $this->routeCollection)
            );

            if($key === false) {
                http_response_code(404);
                header("HTTP/1.0 404 Not Found");
                
                return $this->showErrorPage();
            }

            return call_user_func_array($this->routeCollection[$key]['callable'],[$this->request]);
            
        } catch (\Throwable $th) {
            return $this->showErrorPage();
        }
    }


    /**
     * Return http request method
     *
     * @return string
     */
    private function requestMethod() : string
    {
        return $this->request->getMethod();
    }

    /**
     * Retrun error page
     */
    private function showErrorPage(): void
    {
        require_once  __DIR__.'../../../view/error.view.php';
    }
    
    /**
     * Return path from current url
     *
     * @return string
     */
    private function requestPath() : string
    {
        return $this->request->getUri();
    }

    private function registerRoute(string $httpMethod, string $uri, callable $controllerMethod) 
    {
        $key = array_search(true, array_map(function($item) use ($uri, $httpMethod){
            return $item['path'] === $uri && $item['httpMethod'] === $httpMethod;
         }, $this->routeCollection)
        );

        if (
            in_array($httpMethod, $this->acceptedRequestMethods) && 
            is_callable($controllerMethod) &&
            $key === false) {
          array_push($this->routeCollection, [
              'path' => $uri,
              'httpMethod' => $httpMethod ,
              'callable' => $controllerMethod
          ]);
        }
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
