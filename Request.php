<?php
namespace QueueApp;

class Request{
  
  private $method;
  private $pathArray;
  private $endpoint;
  private $body;
  private $bodyParsed;
  private $queryParams;
  private $parameters;
  
  public function __construct(){
    //var_dump($_SERVER);
    
   if(isset($_SERVER['PATH_INFO'])){
     $rawPath = $_SERVER['PATH_INFO'];
   } else {
     $rawPath = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
   }
    
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->pathArray = explode('/', trim($rawPath,'/'));
    $this->endpoint = $this->pathArray[0];
    $this->body = file_get_contents('php://input');
    
    
    $this->bodyParsed = json_decode($this->body, true);
    
    parse_str($_SERVER['QUERY_STRING'], $this->queryParams);
    
    if($this->bodyParsed === null){
      $this->parameters = $this->queryParams;
      //var_dump($this->parameters);
      //echo "hello";
      
    } else{
      $this->parameters = array_merge($this->bodyParsed, $this->queryParams);
    }
    
    if($this->parameters === null){
      $this->parameters = Array();
    }
  
    
    //var_dump($this);
  }
  
  public function __get($name){
    if(isset($this->$name)){
      return $this->$name;
    } else {
      return null;
    }
  }
  
  public function handleAndGetResponse(){
    $thisAPI = new API();
    if(count($this->pathArray) > 1 ||
       $this->endpoint !== "queue"){
      return new Response(404, "<h1>404 not found</h1><br>No such endpoint");
    }
    
    else if ($this->method === "GET"){
      return $thisAPI->get($this->endpoint, $this->parameters);
    } else if($this->method === "POST"){
      return $thisAPI->post($this->endpoint, $this->parameters);
    }
  }
  
}