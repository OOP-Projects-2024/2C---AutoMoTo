<?php
class Delete {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function deleteCarParts($id) {
        try {
            $sqlString = "DELETE FROM carparts WHERE id = ?";
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute([$id]);

            return array("code" => 400, "data" => null);
        } catch (\PDOException $e) {
            return array("code" => 401, "errmsg" => $e->getMessage());
        }
    }
    public function deleteUsers($id) {
        try {
            $sqlString = "DELETE FROM users WHERE id = ?";
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute([$id]);

            return array("code" => 400, "data" => null);
        } catch (\PDOException $e) {
            return array("code" => 401, "errmsg" => $e->getMessage());
        }
    }
}
?>