<?php
namespace QueueApp;

class Database {
  
  private $elementNames = ["queue"];
  private $filterNames = ["type", "service", "organization", "firstName", "lastName", "day"];
  
  private $ourPDO;
  
  public function __construct(){
    $this->setupPDO();
    //var_dump($this);
  }
  
  private function checkElementName($elementName){
    if(in_array($elementName, $this->elementNames)){
      return true;
    }
    else {
      return false;
    }
  }
  
  private function checkFilterNames($filterName){
    if(in_array($filterName, $this->filterNames)){
      return true;
    }
    else {
      return false;
    }
  }
  
  private function setupPDO(){
    try{
      $this->ourPDO = new \PDO ("mysql:host=localhost;dbname=firmstep_test","root","");
      $this->ourPDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING );
    } 
    catch (PDOException $e) {
      print "Database error: " . $e->getMessage() . "<br/>";
      die();
    }
  }
  
  public function addFilterParamsSQL($SQL, $filter){
    if(is_array($filter) && 
       count($filter) !== 0 ){
     
      $SQL .= " WHERE ";
      foreach($filter as $fieldName => $param){
        if ($this->checkFilterNames($fieldName)){
          
          //day is a special case
          if ($fieldName === "day"){
            $SQL .= "queuedDate >= :day AND queuedDate < :day + INTERVAL 1 DAY";
          } else{
            $SQL .= " $fieldName = :$fieldName";
          }
          
        }
      }
      
    }
    return $SQL;
  }
  
  public function executeSelectAndFetchAll($SQL, $filter){
    $stmt = $this->ourPDO->prepare($SQL);
    
    if(is_array($filter) && 
       count($filter) !== 0 ) {
       foreach ($filter as $fieldName => $param){
         if ($this->checkFilterNames($fieldName)){
           $stmt->bindValue(":".$fieldName, $param);
         }
       }
      
    }
    
    $stmt->execute();
    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    //var_dump($filter);
    //var_dump($data);
    
    //var_dump($this->ourPDO->errorInfo());
    
    return $data;
  }
  
  public function getElements($elementName, $filter){
    $ourElementName = str_replace(' ', '', $elementName);
    
    //element name has whitespace stripped and then explicitly whitelisted before use in query
    if($this->checkElementName($ourElementName)){
      
      $SQL = "SELECT * from $ourElementName";
        
      $SQL = $this->addFilterParamsSQL($SQL, $filter);
      $SQL .= ";";
      try {
        //var_dump($SQL);
        return $this->executeSelectAndFetchAll($SQL,$filter);
      } 
      catch (PDOException $e) {
        print "Database error: " . $e->getMessage() . "<br/>";
        die();
      }
    } else {
      return false;
    }
    
  }
  
  public function insertElement($elementName, $elementData){
    $ourElementName = str_replace(' ', '', $elementName);
    
    //element name has whitespace stripped and then explicitly whitelisted before use in query
    if($this->checkElementName($ourElementName)){
      
      $count = 0;
      $fieldString = "";
      $dataString = "";
      
      foreach ($elementData as $fieldName => $data){
        
        $fieldString .= str_replace(' ', '', $fieldName);
        $valuesString .= ":".str_replace(' ', '',$fieldName);
        
        $count++;
        if($count !== count($elementData)){
          $fieldString .= ", ";
          $valuesString .= ", ";
        }
      }
      
      $SQL = "INSERT into $ourElementName (".$fieldString.") VALUES (".$valuesString.");";
      var_dump($SQL);
      
      $stmt = $this->ourPDO->prepare($SQL);
      
      foreach($elementData as $fieldName => $data){
       
        $stmt->bindValue(":".$fieldName, $data);
      }
      
      try{
        $stmt->execute();
      } 
      catch (PDOException $e) {
        print "Database error: " . $e->getMessage() . "<br/>";
        die();
      }
      
      //if no exception assume that insert occured sucessfully
      return true;
      
    } else{
      return false;
    }
  }
  
}