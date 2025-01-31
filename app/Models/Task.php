<?php

namespace App\Models;

use PDO;

class Task
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllTasksByUser($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTaskById($id, $userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO tasks (user_id, title, description, due_date, status) VALUES (:user_id, :title, :description, :due_date, :status)");
        return $stmt->execute($data);
    }

    public function update($id, $userId, $data)
    {
        $stmt = $this->db->prepare("UPDATE tasks SET title = :title, description = :description, due_date = :due_date, status = :status WHERE id = :id AND user_id = :user_id");
        return $stmt->execute(array_merge(['id' => $id, 'user_id' => $userId], $data));
    }

    public function delete($id, $userId)
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id AND user_id = :user_id");
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }
}
