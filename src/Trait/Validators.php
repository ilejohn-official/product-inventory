<?php

namespace App\Trait;


trait Validators
{
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
        return is_string($cleaned) && $cleaned >= $minlength && $cleaned <= $maxlength;
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
}
