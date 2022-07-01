<?php

namespace App\Trait;

trait Response
{
    /**
    * Return json response
    *
    * @param mixed $data
    * @param int $flags
    * @param int $statusCode
    *
    * @return array|string|null
    */
    public function json(mixed $data, int $flags = 0, int $statusCode = 200)
    {
        header('Content-Type: application/json; charset=utf-8', true, $statusCode);

        echo json_encode($data, $flags);

        die;
    }

    /**
     * Render view in folder
     *
     * @param string $filename
     */
    public function view(string $filename)
    {
        require_once  __DIR__."../../../view/$filename";

        die;
    }
}
