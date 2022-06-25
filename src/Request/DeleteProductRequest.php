<?php

namespace App\Request;

use App\Trait\Validators;

class DeleteProductRequest extends Request
{
   use Validators;

   public function __construct()
   {
       parent::__construct();

       $this->data = $this->getBody();

       $this->rules = [
           'required' => ['ids'],
           'array_of_integers' => ['ids'],
       ];

   }

   public function getValidated() : array
   {
       return $this->getBody();
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

     $this->validateIsArrayOfNumbers();
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

   private function validateIsArrayOfNumbers() : void
   {
    foreach($this->rules['array_of_integers'] as $key)
     {
        if (!$this->array_of_integers($this->data[$key])){
            $this->setError($key, "$key must be an array of integer ids");
        }
    }
   }

}
