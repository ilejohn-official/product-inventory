<?php

namespace App\Request;

use App\Trait\Validators;

class CreateProductRequest extends Request
{
   use Validators;

   private array $data;

   private array $errors = [];

   private array $rules;

   private bool $hasErrors = false;

   public function __construct()
   {
       parent::__construct();

       $this->data = $this->getBody();

       $this->rules = [
           'required' => ['sku', 'name', 'price', 'attribute_key', 'attribute_value', 'attribute_unit'],
           'string' => ['sku', 'name', 'attribute_key', 'attribute_unit'],
           'numeric' => ['price', 'attribute_value']
       ];

   }

   public function getValidated() : array
   {
       return array_map(
        fn($value) => is_numeric($value) ? $value : trim(htmlentities($value)), $this->getBody()
       );
   }

   public function validate()
   {
       $this->validateBody();

       if ($this->hasErrors){
         echo json_encode([
            'status_code' => 422,
            'status' => false,
            'message' => 'Error',
            'data' => $this->getError()
          ]);
          die;
       }
   }


   public function validateBody() : void
   {
     $this->validateRequired();

     if ($this->hasErrors){
        return;
     }

     $this->validateString();
     $this->validateNumber();
   }

   public function hasError() : bool
   {
       return $this->hasErrors;
   }

   public function getError(string $key = null) : array|string|null
   {
     return is_null($key) ? $this->errors : (
          array_key_exists($key, $this->errors) ? $this->errors[$key] : null
     );
   }

   private function setError(string $key, string $message) : void
   {
     array_key_exists($key, $this->errors)
                    ? $this->errors[$key] .= ". ". $message
                    : $this->errors[$key] = $message;

     $this->hasErrors = true;
   }

   private function validateRequired() : void
   {
       foreach($this->rules['required'] as $key)
       {
         if (!$this->required($this->data, $key)){
             $this->setError($key, "$key is required");
         }
       }
   }

   private function validateString() : void
   {
    foreach($this->rules['string'] as $key)
       {
         $min = $key == 'attribute_unit' ? 2 : 3;
         $max = 150;
         if (!$this->string($this->data[$key], $max, $min)){
             $this->setError($key, "$key must be a string between $min and $max chars");
         }
       }
   }

   private function validateNumber() : void
   {
    foreach($this->rules['numeric'] as $key)
       {
         if (!$this->numeric($this->data[$key])){
             $this->setError($key, "$key must be a valid number greater than 0");
         }
       }
   }

}
