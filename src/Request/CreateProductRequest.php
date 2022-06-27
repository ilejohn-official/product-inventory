<?php

namespace App\Request;

use App\Trait\Validators;
use App\Interface\DatabaseInterface;

class CreateProductRequest extends Request
{
   use Validators;

   public function __construct(private DatabaseInterface $db)
   {
       parent::__construct();

       $this->data = $this->getBody();

       $this->rules = [
           'required' => ['sku', 'name', 'price', 'attribute_key', 'attribute_value', 'attribute_unit'],
           'string' => ['sku', 'name', 'attribute_key', 'attribute_unit'],
           'numeric' => ['price', 'attribute_value'],
           'unique' => ['sku:products']
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
        header('Content-Type: application/json; charset=utf-8', TRUE, 406);
         echo json_encode([
            'status_code' => 406,
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
     $this->validateUnique();
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

   private function validateUnique() : void
   {

      foreach($this->rules['unique'] as $key)
       {
          $extract = explode(':', $key);

         if (!$this->unique($this->data, $extract[0], $extract[1], $this->db)){
             $this->setError($extract[0], "$extract[0] must be unique");
         }
       }
   }

}
