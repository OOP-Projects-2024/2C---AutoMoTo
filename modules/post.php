<?php
include_once "Common.php";

class Post extends Common {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function postCarParts($body) {
       
           $result = $this->postData("carparts", $body, $this->pdo);
        if($result['code'] == 200){
          $this->logger("testthunder5", "POST", "New parts was added to the inventory.");
          return $this->generateResponse($result['data'], "Success", "Successfully added a new car parts.", $result['code']);
        }
        $this->logger("testthunder5", "POST", $result['errmsg']);
        return $this->generateResponse(null, "Failed", $result['errmsg'], $result['code']);
    }
}
?>
