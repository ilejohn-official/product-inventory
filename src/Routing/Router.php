<?php

namespace App\Routing;

class Router
{
    private $routeName;
    private $routeList = [];
    private $method;
    private $controllerMethod;
    private $arguments = [];
    private $acceptedRequestMethods = ['get', 'post', 'put', 'delete'];
    private $httpMethod = 'get';
    private $route = [];
    private $request;
    private $requestVarName = 'request';

    /**
     * Run the router
     *
     * @return void
     */
    public function run()
    {
        try {
            
            // Check http verb of current request
            if(!$this->checkHttpMethod()) {
                // Exception
                http_response_code(404);
                header("HTTP/1.0 404 Not Found");
                
                return $this->showErrorPage();
                //throw new \Exception('404 Not Found');
                exit();
            }

            // Get and invert route argument array
            $this->arguments = $this->route['segments'];

            // Defining controller and method
            $controllerName = $this->route['callable'][0];
            $methodName = $this->route['callable'][1];

            // Invoke array of parameters of the method to be called
            $ReflectionMethod =  new \ReflectionMethod($controllerName, $methodName);
            $params = $ReflectionMethod->getParameters();
            $paramNames = array_map(function( $item ){
                return $item->getName();
            }, $params);

            // Checks for request as method argument
            if(isset($paramNames[0]) && $paramNames[0] == $this->requestVarName && $this->request)
            {
                // Add request object to argument list
                $this->arguments = array_reverse($this->arguments);
                array_push($this->arguments, $this->request);
                $this->arguments = array_reverse($this->arguments);
            }

            // Validating data type of parameters
            foreach ($params as $key => $p)
            {
                // Checks for configured type
                if($p->getType()) {
                    // Check if it is an integer
                    if(isset($this->arguments[$key]))
                    {
                        if($p->getType()->getName() == 'int')
                        {
                            if (!is_numeric($this->arguments[$key])) {
                                throw new \InvalidArgumentException('Argument '.($key+1).' passed to Router must be of the type '.$p->getType()->getName().', '.gettype($this->arguments[$key]).' given.');
                            }
        
                        // Check if it's a string
                        } elseif($p->getType()->getName() == 'string') {
                            if (!is_string($this->arguments[$key])) {
                                throw new \InvalidArgumentException('Argument '.($key+1).'2 passed to Router must be of the type '.$p->getType()->getName().', '.gettype($this->arguments[$key]).' given.');
                            }
                        }
                    }
                    
                }
            }
        
            return call_user_func_array([new $controllerName(), $methodName], $this->arguments);
            
        } catch (\Throwable $th) {
            return $this->showErrorPage();
            throw $th;

        } catch (\Exception $e) {
            return $this->showErrorPage();
            throw $e;
        }
    }

    /**
     * Return http request method
     *
     * @return string
     */
    private function requestMethod() : string
    {
        return $_SERVER['REQUEST_METHOD'];
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
    private function request_path() : string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Checks and sets http verb
     *
     * @return boolean
     */
    public function checkHttpMethod(): bool
    {
        if($this->requestMethod() == 'POST')
            $this->httpMethod = (isset($_POST['_method']) && in_array($_POST['_method'], $this->acceptedRequestMethods)) ? $_POST['_method'] : 'post';

        if(!$this->route)
            return false;

        return $this->route['http_method'] == $this->httpMethod ? true : false;
    }

    /**
     * Defines route name and arguments that will be passed
     *
     * @param array $route
     * @param array $segments_map
     * @return void
     */
    private function setRoute(array $route, array $segments_map) : void
    {
        $array_url = explode('/', $this->request_path());
        array_shift($array_url);

        $args = [];
        
        foreach ($segments_map as $key => $segment) {
            if(array_key_exists($key, $array_url)) {
                array_push($args, $array_url[$key]);
                $array_url[$key] = $segment;
            }
        }

        $routeName = '/' . implode('/', $array_url);

        $route['segments'] = $args;
        
        
        if($routeName == $route['path'] )
        {
            if(!isset($this->routeList[$this->httpMethod]) || !array_key_exists($this->request_path(), $this->routeList[$this->httpMethod]))
            {
                $this->route = $route;
            }
        }

        // Inserting in the route list
        $this->routeList[$route['http_method']][$route['path']] = $route;
    }

    /**
     * Creates a key map of the parameters passed in the route
     *
     * @param string $path
     * @param array $segments_map
     * @return array
     */
    private function createSegmentsMap(string $path) : array
    {
        $segments_map = [];

        $array_path = explode('/', $path);
        array_shift($array_path);

        foreach($array_path as $key => $segment) {
          if (preg_match('/{(.*?)}/', $segment)){
            $segments_map[$key] = $segment;
          }   
        }

        return $segments_map;
    }

    private function registerRoute(string $httpMethod, string $uri, callable $controllerMethod) 
    {
        $route['path'] = $uri;
        $route['callable'] = $controllerMethod;
        $route['http_method'] = $httpMethod;

        $segments_map = $this->createSegmentsMap($uri);

        $this->setRoute($route, $segments_map);
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
        $this->registerRoute('get', $path, $controllerMethod);
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
        $this->registerRoute('post', $path, $controllerMethod);
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
        $this->registerRoute('put', $path, $controllerMethod);
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
        $this->registerRoute('delete', $path, $controllerMethod);
    }
}
