<?php
include_once "common.php";
include "auth.php";

class Get extends Common {

    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getaccounts($id = null) {
        $condition = "isdeleted = 0";
        if ($id !== null) {
            $condition .= " AND id=" . $id;
        }

        $result = $this->getDataByTable('users', $condition, $this->pdo);
        if ($result['code'] == 200) {
            return $this->generateResponse($result['data'], "Success", "Successfully retrieved records.", $result['code']);
        }
        return $this->generateResponse(null, "Failed", $result['errmsg'], $result['code']);
    }
    public function getLogs($date) {
        $filename = "./logs/" . $date . ".log";
        $logs = array();

        try {
            $file = new SplFileObject($filename);
            while (!$file->eof()) {
                array_push($logs, $file->fgets());
            }
            $remarks = "Success";
            $message = "Successfully retrieved logs.";
        } catch (Exception $e) {
            $remarks = "Failed";
            $message = $e->getMessage();
        }
        return $this->generateResponse(array("logs" => $logs), $remarks, $message, 200);
    }

    public function getCarParts($id = null) {
        $sqlString = "SELECT * FROM carparts";
        if ($id) {
            $sqlString .= " WHERE id = ?";
        }

        try {
            $stmt = $this->pdo->prepare($sqlString);
            if ($id) {
                $stmt->execute([$id]);
            } else {
                $stmt->execute();
            }

            $data = $stmt->fetchAll();
            return array("code" => 200, "data" => $data);
        } catch (\PDOException $e) {
            return array("code" => 401, "errmsg" => $e->getMessage());
        }
    }
}
?>
