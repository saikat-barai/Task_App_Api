<?php

namespace Api\TaskApi;

class Task{
    private $conn;
    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    // get all tasks 
    public function getAllTasks(){
        $result = $this->conn->query("SELECT * FROM tasks");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // get single task
    public function getTask($id){
        $id = intval($id);
        $query = "SELECT * FROM tasks WHERE id = $id";
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }

    // create a new task 
    public function createTask($data){
        $title = $data['title'];
        $description = $data['description'] ?? '';
        $priority = $data['priority'] ?? 'low';

        $query = "INSERT INTO tasks(title, description, priority) VALUES('$title', '$description', 'priority')";
        
        if ($this->conn->query($query)) {
            return ["message" => "Task created successfully."];
        }
        return ["error" => "Failed to create task."];
    }


    // update task
    public function updateTask($id, $data){
        $id = intval($id);
        $result = $this->conn->query("SELECT * FROM tasks WHERE id = $id");
        if ($result->num_rows === 0) {
            http_response_code(404);
            return ["error" => "Task not found."];
        }
        $existingTask = $result->fetch_assoc();


        // updating 

        $title = isset($data['title']) ? $data['title'] : $existingTask['title'];
        $description = isset($data['description']) ? $data['description'] : $existingTask['description'];
        $priority = isset($data['priority']) ? $data['priority'] : $existingTask['priority'];
        $is_completed = isset($data['is_completed']) ? $data['is_completed'] : $existingTask['is_completed'];

        $query = "UPDATE tasks SET title = '$title', description = '$description', priority = '$priority', is_completed = '$is_completed' WHERE id = $id";

        if ($this->conn->query($query)) {
            return ["message" => "Task updated successfully."];
        }
        return ["error" => "Failed to update task."];
    }


    // delete task 
    public function deleteTask($id){
        $id = intval($id);
        $query = "DELETE FROM tasks WHERE id = $id";
        if ($this->conn->query($query)) {
            return ["message" => "Task deleted successfully."];
        }
        return ["error" => "Failed to delete task."];
    }
}