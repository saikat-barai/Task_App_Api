<?php

use Api\TaskApi\Router;
use Config\Database;
use Api\TaskApi\Task;

require_once './vendor/autoload.php';


$db = new Database();
$conn = $db->getConnection();
$task = new Task($conn);
$router = new Router($task);
$router->handleRequest();
$conn->close();