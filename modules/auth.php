<?php
class Authentication {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function isAuthorized() {
        $headers = array_change_key_case(getallheaders(), CASE_LOWER);

        if (!isset($headers['authorization']) || !isset($headers['x-auth-user'])) {
            return false; 
        }

        $token = $this->getToken($headers['x-auth-user']);
        return $token && $token === $headers['authorization'];
    }

    private function getToken($username) {
        try {
            $stmt = $this->pdo->prepare("SELECT token FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result['token'])) {
                return $result['token'];  
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return null;
    }

    private function generateHeader() {
        $header = [
            "typ" => "JWT",
            "alg" => "HS256",
            "app" => "lib",
            "dev" => "Jeilo and Harvey"
        ];
        return base64_encode(json_encode($header));
    }

    private function generatePayload($id, $username) {
        $payload = [
            "uid" => $id,
            "uc" => $username,
            "email" => "202312290@gordoncollege.edu.ph",
            "date" => date_create(),
            "exp" => date("Y-m-d H:i:s")
        ];
        return base64_encode(json_encode($payload));
    }

    private function generateToken($id, $username) {
        $header = $this->generateHeader();
        $payload = $this->generatePayload($id, $username);
        $signature = hash_hmac("sha256", "$header.$payload", TOKEN_KEY);
        return "$header.$payload." . base64_encode($signature);
    }

    private function encryptPassword($password) {
        $hashFormat = "$2y$10$"; 
        return password_hash($password, PASSWORD_BCRYPT);  
    }

    private function isSamePassword($inputPassword, $existingHash) {
        return password_verify($inputPassword, $existingHash);
    }

    public function login($body) {
        $username = $body['username'];
        $password = $body['password'];
        $response = ["code" => 401, "message" => "Invalid credentials."];

        try {
            $sql = "SELECT id, username, password FROM users WHERE username=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch();
                if ($this->isSamePassword($password, $user['password'])) {
                    $token = $this->generateToken($user['id'], $user['username']);
                    $token_arr = explode('.', $token);

                    $this->saveToken($token_arr[2], $user['username']);
                    $payload = [
                        "id" => $user['id'],
                        "username" => $user['username'],
                        "token" => $token_arr[2]
                    ];

                    $response = [
                        "code" => 200,
                        "message" => "Login successful.",
                        "data" => $payload
                    ];
                }
            }
        } catch (\PDOException $e) {
            $response["message"] = $e->getMessage();
        }
        return $response;
    }

    public function addAccount($body) {
        $password = $this->encryptPassword($body['password']);  

        try {
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$body['username'], $password]);

            $userId = $this->pdo->lastInsertId();
            $username = $body['username'];

            $token = $this->generateToken($userId, $username);
            $token_arr = explode('.', $token);

            $this->saveToken($token_arr[2], $username);

            return [
                "code" => 200,
                "message" => "Account created successfully.",
                "data" => [
                    "username" => $username,
                    "token" => $token_arr[2]
                ]
            ];
        } catch (\PDOException $e) {
            return ["code" => 400, "message" => $e->getMessage()];
        }
    }

    private function saveToken($token, $username) {
        $sql = "UPDATE users SET token=? WHERE username=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$token, $username]);
    }

    public function updateUser($body, $userId) {
        try {
            $updates = [];
            $params = [];

            if (isset($body->username)) {
                $updates[] = "username = ?";
                $params[] = $body->username;
            }

            if (isset($body->password)) {
                $updates[] = "password = ?";
                $params[] = $this->encryptPassword($body->password); 
            }

            if (empty($updates)) {
                return ["code" => 400, "message" => "No valid fields to update"];
            }

            $updatesQuery = implode(", ", $updates);
            $sql = "UPDATE users SET $updatesQuery WHERE id = ?";
            $params[] = $userId;

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return ["code" => 200, "message" => "User updated successfully"];
        } catch (\PDOException $e) {
            return ["code" => 400, "message" => $e->getMessage()];
        }
    }
}
?>
