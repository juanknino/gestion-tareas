<?php

namespace App\Controllers;

use App\Models\Task;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;

class TaskController
{
    private $db;

    public function __construct()
    {
        $config = include __DIR__ . '/../../config/database.php';
        $dsn = "mysql:host={$config['connection']['host']};dbname={$config['connection']['database']};charset={$config['connection']['charset']}";
        $this->db = new PDO($dsn, $config['connection']['username'], $config['connection']['password']);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function authenticate()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["message" => "Authorization header missing"]);
            exit;
        }

        $token = str_replace("Bearer ", "", $headers['Authorization']);

        try {
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
            return $decoded->sub;
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid token"]);
            exit;
        }
    }

    public function index()
    {
        $userId = $this->authenticate();
        $taskModel = new Task($this->db);
        echo json_encode($taskModel->getAllTasksByUser($userId));
    }

    public function store()
    {
        $userId = $this->authenticate();
        $data = json_decode(file_get_contents('php://input'), true);
        $data['user_id'] = $userId;

        $taskModel = new Task($this->db);
        $taskModel->create($data);

        echo json_encode(["message" => "Task created successfully"]);
    }

    public function show($id)
    {
        $userId = $this->authenticate();
        $taskModel = new Task($this->db);
        $task = $taskModel->getTaskById($id, $userId);

        if ($task) {
            echo json_encode($task);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Task not found"]);
        }
    }

    public function update($id)
    {
        $userId = $this->authenticate();
        $data = json_decode(file_get_contents('php://input'), true);

        $taskModel = new Task($this->db);
        $taskModel->update($id, $userId, $data);

        echo json_encode(["message" => "Task updated successfully"]);
    }

    public function destroy($id)
    {
        $userId = $this->authenticate();
        $taskModel = new Task($this->db);
        $taskModel->delete($id, $userId);

        echo json_encode(["message" => "Task deleted successfully"]);
    }
}
