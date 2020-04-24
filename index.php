<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-type: application/json');

require 'api/db.php';
require 'api/lib.php';

$method = $_SERVER['REQUEST_METHOD'];
$query = $_GET['q'];
$params = explode('/', $query);
[$type, $id] = $params;

if ($method == 'GET' && $type == 'posts' && empty($id)) {
    getPosts($db);
} elseif ($method == 'GET' && $type == 'post' && isset($id)) {
    getPost($db, $id);
}
// else {
//     http_response_code(404);
//     $error = [
//         'status' => false,
//         'message' => 'Wrong request!'
//     ];
//     echo json_encode($error);
// }

if ($method == 'POST' && $type == 'post') {
    addPost($db, $_POST);
}

if ($method == 'PATCH' && $type == 'post' && !empty($id)) {
    $data = json_decode(file_get_contents('php://input'), true);
    updatePost($db, $data, $id);
}
if ($method == 'DELETE' && $type == 'post' && !empty($id)) {
    deletePost($db, $id);
}
