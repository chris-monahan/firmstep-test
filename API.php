<?php
namespace QueueApp;

class API{
  
  private $database;
  
  public function __construct(){
    $this->database = new Database();
  }
  
  public function get($endpoint, $parameters){
    
    if($endpoint === "queue"){
      if(!isset($parameters['day'])){
        $parameters['day'] = date("Y-m-d");
      } else {
        $phpDate = new \DateTime($parameters['day']);
        $parameters['day'] === $phpDate->format("Y-m-d");
      }
    }
    
    $data = $this->database->getElements($endpoint, $parameters);
    if($data === false){
      return new NotFoundResponse("<h1>404 not found</h1>Endpoint not found");
    } else {
      return new JsonResponse (200, $data);
    }

  }
  
public function post($endpoint, $parameters){
  
  var_dump($parameters);
  
  if($endpoint === "queue"){
    $validTypes = ["Citizen", "Anonymous"];
    $validServices = ["Council Tax","Benefits","Rent"];

    if( !isset($parameters["type"]) ||
        !isset($parameters["service"]) ||
        ( ($parameters["type"] === "Citizen") && 
          (!isset($parameters["firstName"]) || !isset($parameters["lastName"]))) ){
      $response = new JsonResponse(400, json_encode(["response" => "failure: parameters missing"]));
      
    } else if(!in_array($parameters["type"], $validTypes)) {
      $response = new JsonResponse(400, ["response" => "failure: invalid 'type' parameter"]);
    } else if(!in_array($parameters["service"], $validServices)){
      $response = new JsonResponse(400, ["response" => "failure: invalid 'service' parameter"]);
    } else{
      
    
      //whitelist parameter names
      $wlParameters = array(
        "type" => $parameters["type"],
        "firstName" => $parameters["firstName"],
        "lastName" => $parameters["lastName"],
        "organization" => $parameters["organization"],
        "service" => $parameters["service"]
      );


      $data = $this->database->insertElement($endpoint, $wlParameters);
      if($data === false){
        $response = new NotFoundResponse("<h1>404 not found</h1>Endpoint not found");
      } else {
        $response = new JsonResponse (200, json_encode(["response" => "success"]));
      }
    }
    
    //var_dump($response);
    
    return $response;
    
   }

  }
}