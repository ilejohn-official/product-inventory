<?php

namespace App\Routing;

use App\Interface\RequestInterface;
use App\Interface\RouteCollectionInterface;

class Router
{

    public function __construct(private RequestInterface $request)
    {

    }

    /**
     * Process the router
     *
     * @return callable
     */
    public function __invoke(RouteCollectionInterface $routes) : callable
    {
        $routeCollection = $routes->getRoutes();

        try {
            $key = array_search(true, array_map(function($item){
                return ( 
                    $item['path'] == $this->requestPath() && 
                    $item['httpMethod'] == $this->requestMethod()
                );
             }, $routeCollection)
            );

            if($key === false) {
                http_response_code(404);
                header("HTTP/1.0 404 Not Found");
                
                return $this->showErrorPage();
            }
            
            return $routeCollection[$key]['callable'];
            
        } catch (\Throwable $th) {
            //throw $th;
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
}
