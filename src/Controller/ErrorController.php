<?php

namespace App\Controller;

class ErrorController 
{
  

  public function __construct() 
  {
    
  }

  /**
   * Return error page
   * @return void
  */
  public static  function showErrorPage(): void
  {
        require_once  __DIR__.'../../../view/error.view.php';
  }

}
