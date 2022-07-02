# Product Inventory

## Table of contents

- [General Info](#general-info)
- [Dependencies](#dependencies)
- [Requirements](#requirements)
- [Setup](#setup)
- [Usage](#usage)

## General Info

This is a simple product inventory application built with plain php and Vue 3( without build)

## Dependencies

- [phpdotenv](https://github.com/vlucas/phpdotenv "Dotenv")
- [yiisoft/di](https://github.com/yiisoft/di "Yii Dependency Injection")
- [yiisoft/injector](https://github.com/yiisoft/injector "Yii Injector")

## Requirements

- [PHP >= 7.4](https://www.php.net/ "PHP")

## Setup

- Clone the project and navigate to it's root path and install the required dependency packages using either of the below commands on the terminal/command line interface

  ```bash
  git clone https://github.com/ilejohn-official/product-inventory.git
  cd product-inventory
  ```

  ```
  composer install
  ```

- Copy and paste the content of the .env.example file into a new file named .env in the same directory as the former and set it's  
  values based on your environment's configuration.

- Create a table using the query in [/config/migration.php](https://github.com/ilejohn-official/product-inventory/blob/master/config/migration.php)

## Usage

- To run local server.

  Use the php internally provisioned server or you can use use apache

  ```
  php -S localhost:8000
  ```

  | Route        | Function               |
  | ------------ | ---------------------- |
  | /            | Show product list page |
  | /add-product | Page to add products   |
