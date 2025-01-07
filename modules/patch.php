<?php
class Patch {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    private function encryptPassword($password) {
        $hashFormat = "$2y$10$"; //blowfish
        // bcrypt hash with cost factor 10
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
    public function patchCarParts($body, $id) {
        try {
            $sqlString = "UPDATE carparts SET parts = ?, price = ?, quantity = ?, partsdescription = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute([
                $body->parts,
                $body->price,
                $body->quantity,
                $body->partsdescription,
                $id
            ]);

            return array("code" => 400, "data" => null);
        } catch (\PDOException $e) {
            return array("code" => 401, "errmsg" => $e->getMessage());
        }
    }

    public function patchUsers($body, $id) {
        try {
            // check if the password is set and hash it
            if (isset($body->password)) {
                $body->password = $this->encryptPassword($body->password);  // encrypt password
            }
    
            $sqlString = "UPDATE users SET username = ?, password = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute([
                $body->username,
                $body->password,  // hashed password
                $id
            ]);
    
            return array("code" => 200, "data" => null);  // return success code
        } catch (\PDOException $e) {
            return array("code" => 401, "errmsg" => $e->getMessage());
        }
    }
}
?>