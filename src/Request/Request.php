<?php

namespace App\Request;

use App\Interface\RequestInterface;

class Request implements RequestInterface
{
    /**
     * Request body parameters ($_POST).
     *
     * @var array
     */
    private $body;

    /**
     * Query string parameters ($_GET).
     *
     * @var array
     */
    private $query;

    /**
     * @var string
     */
    private $requestUri;

    /**
     * @var string
     */
    private $method;

    /**
     * Accepted Request method
     * @var array
     */
    private $acceptedRequestMethods = ['GET', 'POST', 'PUT', 'DELETE'];

    public function __construct()
    {
        $this->method = $this->requestMethod();
        $this->requestUri = $this->requestUri();
        $this->query = $this->requestQuery();
        $this->body = $this->requestBody();
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
     * Return path from current url
     *
     * @return string
     */
    private function requestUri() : string
    {
        return htmlspecialchars(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }

    /**
     * Return query data
     *
     * @return string
     */
    private function requestQuery() : array
    {
        return $_GET ?? [];
    }

    /**
     * Return post data
     *
     * @return string
     */
    private function requestBody() : array
    {
        return $_POST ?? [];
    }

    /**
     * Set request body
     *
     * @return void
     */
    public function setBody(array $body) : void
    {
         $this->body = $body;
    }

    /**
     * Get request body
     * 
     * @return array
     */
    public function getBody() : array
    {
        return $this->body;
    }

     /**
     * Set request query
     *
     * @return void
     */
    public function setQuery(array $body) : void
    {
         $this->query = $body;
    }

    /**
     * Get request query
     * 
     * @return array
     */
    public function getQuery() : array
    {
        return $this->query;
    }

    /**
     * Get request url
     * 
     * @return string
     */
    public function getUri() : string
    {
        return htmlspecialchars($this->requestUri);
    }

     /**
     * Get request method
     * 
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }
}
