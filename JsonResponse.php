<?php

namespace QueueApp;

class JsonResponse extends Response{
  public function __construct($code, $data = "{}", $contentType = "application/json"){
    
    if(is_array($data)){
      $data = json_encode($data);
    }
    
    parent::__construct($code, $data, $contentType);
    
  }
}