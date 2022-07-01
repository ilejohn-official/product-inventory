<?php

namespace App\Trait;

use App\Interface\DatabaseInterface;

trait Validators
{
    private array $data;

    private array $errors = [];
 
    private array $rules;

    /**
     * check if errors exist
     *
     * @return bool
     */
    public function hasError() : bool
    {
        return count($this->errors) > 0;
    }

    /**
     * Get errors
     *
     * @param string $key
     *
     * @return array|string|null
     */
    public function getError(string $key = null) : array|string|null
    {
        return is_null($key) ? $this->errors : (
            array_key_exists($key, $this->errors) ? $this->errors[$key] : null
        );
    }

    /**
     *  Set errors
     *
     *  @param string $key
     *  @param string $message
     */
    private function setError(string $key, string $message) : void
    {
        array_key_exists($key, $this->errors)
                        ? $this->errors[$key] .= ". ". $message
                        : $this->errors[$key] = $message;
    }

    /**
     * Checks if the key is set and is not null in the array
     *
     * @param array $data
     * @param string $key
     *
     * @return bool
     */
    public function required($data, $key) : bool
    {
        return isset($data[$key]);
    }

    /**
    * Checks if the value of the key exists already on the data store
    *
    * @param array $data
    * @param string $key
    * @param string $table
    *
    * @param App\Interface\DatabaseInterface
    *
    * @return bool
    */
    public function unique($data, $key, $table, DatabaseInterface $db) : bool
    {
        $db->setTable($table);

        return $db->exists($key, $data[$key]) < 1;
    }

    /**
     * Checks if value is a valid string within the given length
     *
     * @param string $string
     * @param int $maxlength
     * @param int $minlength
     *
     * @return bool
     */
    public function string($string, $maxlength=150, $minlength=3) : bool
    {
        $cleaned = strlen(trim(htmlspecialchars($string)));
        return is_string($string) && $cleaned >= $minlength && $cleaned <= $maxlength;
    }

    /**
    * Checks if value is a valid number
    *
    * @param int $number
    *
    * @return bool
    */
    public function numeric($number) : bool
    {
        return is_numeric(htmlspecialchars($number)) && $number > 0;
    }

    /**
     * Tell whether all members of $array are integers.
     *
     * @param array $array
     *
     * @return bool
     */
    public function array_of_integers($array) : bool
    {
        return is_array($array) ? array_filter($array, 'is_int') === $array || array_filter($array, 'ctype_digit') === $array : false;
    }
}
