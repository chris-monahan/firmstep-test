<?php
namespace QueueApp;

class API{
  
  private $database;
  
  public function __construct(){
    $this->database = new Database();
  }
  
  public function get($endpoint, $parameters){
    
    $data = $this->database->getElements($endpoint, $parameters);
    if($data === false){
      return new NotFoundResponse("<h1>404 not found</h1>Endpoint not found");
    } else {
      return new Response (200, json_encode($data));
    }

  }
  
public function post($endpoint, $parameters){
    
    $realParameters = array(
      "type" => $parameters["type"],
      "firstName" => $parameters["firstName"],
      "lastName" => $parameters["lastName"],
      "organization" => $parameters["organization"],
      "service" => $parameters["service"]
    );
    $data = $this->database->insertElement($endpoint, $realParameters);
    if($data === false){
      return new NotFoundResponse("<h1>404 not found</h1>Endpoint not found");
    } else {
      return new Response (200, json_encode(["response" => "success"]));
    }

  }
}