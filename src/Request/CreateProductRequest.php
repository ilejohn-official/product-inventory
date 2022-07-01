<?php

namespace App\Request;

use App\Trait\Validators;
use App\Interface\DatabaseInterface;
use App\Trait\Response;

class CreateProductRequest extends Request
{
    use Validators, Response;

    public function __construct(private DatabaseInterface $db)
    {
        parent::__construct();

        $this->setBody(array_replace($this->getBody(), [
            'attributeValue' =>  json_decode($this->getBody()['attributeValue'], true)
        ]));

        $this->data = $this->getBody();
       
        $this->rules = [
           'required' => ['sku', 'name', 'price', 'attributeValue'],
           'string' => ['sku', 'name'],
           'numeric' => ['price'],
           'unique' => ['sku:products']
       ];
    }

    public function getValidated() : array
    {
        return array_map(
            fn ($value) => is_numeric($value) || is_array($value) ? $value : trim(htmlentities($value)),
            $this->getBody()
        );
    }

    public function validate()
    {
        $this->validateBody();

        if ($this->hasError()) {
            $this->json(
                [
                'status_code' => 406,
                'status' => false,
                'message' => 'Error',
                'data' => $this->getError()
            ],
                0,
                406
            );
        }
    }


    public function validateBody() : void
    {
        $this->validateRequired();

        if ($this->hasError()) {
            return;
        }

        $this->validateString();
        $this->validateNumber();
        $this->validateUnique();
        $this->validateAttribute();
    }

    private function validateRequired() : void
    {
        foreach ($this->rules['required'] as $key) {
            if (!$this->required($this->data, $key)) {
                $this->setError($key, "$key is required");
            }
        }
    }

    private function validateString() : void
    {
        foreach ($this->rules['string'] as $key) {
            $min = 3;
            $max = 150;
            if (!$this->string($this->data[$key], $max, $min)) {
                $this->setError($key, "$key must be a string between $min and $max chars");
            }
        }
    }

    private function validateNumber() : void
    {
        foreach ($this->rules['numeric'] as $key) {
            if (!$this->numeric($this->data[$key])) {
                $this->setError($key, "$key must be a valid number greater than 0");
            }
        }
    }

    private function validateUnique() : void
    {
        foreach ($this->rules['unique'] as $key) {
            $extract = explode(':', $key);

            if (!$this->unique($this->data, $extract[0], $extract[1], $this->db)) {
                $this->setError($extract[0], "$extract[0] must be unique");
            }
        }
    }

    private function validateAttribute() : void
    {
        if (count($this->data['attributeValue']) < $this->data['productType_measureCount']) {
            $this->setError('attributeValue', "attributeValue unit(s) required");
        }

        foreach ($this->data['attributeValue'] as $value) {
            if (!$this->numeric($value)) {
                $this->setError('attributeValue', "attributeValue unit(s) must be a valid number greater than 0");
            }
        }
    }
}
