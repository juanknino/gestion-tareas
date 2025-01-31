<?php

namespace App\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;

class AuthController
{
    private $db;

    public function __construct()
    {
        $config = include __DIR__ . '/../../config/database.php';
        $dsn = "mysql:host={$config['connection']['host']};dbname={$config['connection']['database']};charset={$config['connection']['charset']}";
        $this->db = new PDO($dsn, $config['connection']['username'], $config['connection']['password']);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $userModel = new User($this->db);
        $userModel->create($data);

        echo json_encode(['message' => 'User registered successfully']);
    }

    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new User($this->db);
        $user = $userModel->findByEmail($data['email']);

        if ($user && password_verify($data['password'], $user['password'])) {
            $payload = [
                'iss' => 'localhost',
                'sub' => $user['id'],
                'iat' => time(),
                'exp' => time() + (60 * 60) // 1 hora
            ];

            $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
            echo json_encode(['token' => $jwt]);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
        }
    }
}