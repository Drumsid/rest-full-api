<?php

function niceDebug($data)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

function getPosts($connect)
{
    $query = "SELECT * FROM `posts`";
    $result = mysqli_query($connect, $query) or die("Ошибка " . mysqli_error($connect));

    while ($post = mysqli_fetch_assoc($result)) {
        $posts[] = $post;
    }

    echo json_encode($posts);
}

function getPost($connect, $id)
{
    $post = issetPost($connect, $id);
    if ($post) {
        http_response_code(200);
        echo json_encode($post);
    } else {
        http_response_code(404);
        $error = [
            'status' => false,
            'message' => 'Post not found!'
        ];
        echo json_encode($error);
    }
}

function addPost($connect, $data)
{
    ['title' => $title, 'body' => $body] = validatePost($data);
    $query = "INSERT INTO `posts` (`title`, `body`) VALUES('$title', '$body')";
    $result = mysqli_query($connect, $query);
    if ($result) {
        http_response_code(201);
        $error = [
            'status' => true,
            'message' => mysqli_insert_id($connect)
        ];
        echo json_encode($error);
    } else {
        http_response_code(404);
        $error = [
            'status' => false,
            'message' => 'Error!'
        ];
        echo json_encode($error);
    }
}

function updatePost($connect, $data, $id)
{
    $post = issetPost($connect, $id);
    if ($post) {
        if (! empty($data['title']) || ! empty($data['body'])) {
            ['title' => $title, 'body' => $body] = validatePost($data);
            $query = "UPDATE `posts` SET `title` = '$title', `body` = '$body' WHERE `posts`.`id`='$id'";
            $result = mysqli_query($connect, $query) or die("Ошибка " . mysqli_error($connect));
            http_response_code(200);
            $error = [
                'id' => $id,
                'status' => true,
                'message' => 'Post updated'
            ];
            echo json_encode($error);
        } else {
            http_response_code(400);
            $error = [
                'status' => false,
                'message' => 'input is empty!'
            ];
            echo json_encode($error);
        }
    } else {
        http_response_code(404);
        $error = [
            'status' => false,
            'message' => 'Post not found!'
        ];
        echo json_encode($error);
    }
}

function deletePost($connect, $id)
{
    $post = issetPost($connect, $id);
    if ($post) {
        $query = "DELETE FROM `posts` WHERE `posts`.`id`='$id'";
        $result = mysqli_query($connect, $query);
        http_response_code(200);
        $error = [
            'id' => $id,
            'status' => true,
            'message' => 'Post deleted'
        ];
        echo json_encode($error);
    } else {
        http_response_code(404);
        $error = [
            'status' => false,
            'message' => 'Post not found!'
        ];
        echo json_encode($error);
    }
}

function issetPost($connect, $id)
{
    if (! preg_match('#^[0-9]+$#', $id)) {
        return false;
    }
    $query = "SELECT * FROM `posts` WHERE id=$id";
    $result = mysqli_query($connect, $query);
    $post = mysqli_fetch_assoc($result);
    return $post;
}

function clean($value)
{
    $value = trim($value);
    $value = htmlspecialchars($value);
    return $value;
}

function validatePost($data)
{
    $result = [];
    foreach ($data as $key => $value) {
        $result[$key] = clean($value);
    }
    return $result;
}
