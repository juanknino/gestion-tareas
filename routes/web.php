<?php

use App\Controllers\AuthController;
use App\Controllers\TaskController;

return [
    // Rutas de autenticación
    'POST /register' => [AuthController::class, 'register'],
    'POST /login' => [AuthController::class, 'login'],

    // Rutas de tareas (CRUD)
    'GET /tasks' => [TaskController::class, 'index'],
    'POST /tasks' => [TaskController::class, 'store'],
    'GET /tasks/{id}' => [TaskController::class, 'show'],
    'PUT /tasks/{id}' => [TaskController::class, 'update'],
    'DELETE /tasks/{id}' => [TaskController::class, 'destroy'],
];