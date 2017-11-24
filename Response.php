<?php
namespace QueueApp;

class Response{
  private $body;
  private $responseCode;
  private $responseContentType;
  
  public function __construct($responseCode, $body, $responseContentType = "text/html"){
    $this->body = $body;
    $this->responseCode = $responseCode;
    $this->responseContentType = $responseContentType;
  }
  
  public function serve(){
    http_response_code($this->responseCode);
    header("Content-Type: ".$this->responseContentType);
    echo $this->body;
  }
}