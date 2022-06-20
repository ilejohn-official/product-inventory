<?php

namespace App\Interface;

interface RequestInterface
{

    /**
     * Set request body
     *
     * @return void
     */
    public function setBody(array $body) : void;

    /**
     * Get request body
     * 
     * @return array
     */
    public function getBody() : array;

     /**
     * Set request query
     *
     * @return void
     */
    public function setQuery(array $body) : void;

    /**
     * Get request query
     * 
     * @return array
     */
    public function getQuery() : array;

    /**
     * Get request url
     * 
     * @return string
     */
    public function getUri() : string;

     /**
     * Get request method
     * 
     * @return string
     */
    public function getMethod() : string;
}
