<?php

namespace QueueApp;

class NotFoundResponse extends Response{
  public function __construct($message = "<h1>404 not found</h1>", $contentType ="text/html"){
    
    $code = 404;
    
    parent::__construct($code, $message, $contentType);
    
  }
}