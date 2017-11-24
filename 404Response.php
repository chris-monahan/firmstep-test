<?php

namespace QueueApp;

class NotFoundResponse extends Response{
  public function __construct($message, $contentType){
    
    $code = 404;
    if(!isset($message)){
      $message = "<h1>404 not found</h1>";
      
    }
    
    if(!isset($contentType)){
      $contentType = "text/html";
    }
    
    parent::__construct($code, $message, $contentType);
    
  }
}