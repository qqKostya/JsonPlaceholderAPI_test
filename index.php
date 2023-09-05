<?php
class JsonPlaceholderAPI
{
    const BASE_URL = "https://jsonplaceholder.typicode.com";

    public function getUsers()
    {
        $url = self::BASE_URL . "/users";
        return $this->makeRequest($url);
    }

    public function getUserPosts($userId)
    {
        $url = self::BASE_URL . "/users/{$userId}/posts";
        return $this->makeRequest($url);
    }

    public function getUserTodos($userId)
    {
        $url = self::BASE_URL . "/users/{$userId}/todos";
        return $this->makeRequest($url);
    }

    public function getPost($postId)
    {
        $url = self::BASE_URL . "/posts/{$postId}";
        return $this->makeRequest($url);
    }

    public function createPost($data)
    {
        $url = self::BASE_URL . "/posts";
        return $this->makeRequest($url, 'POST', $data);
    }

    public function updatePost($postId, $data)
    {
        $url = self::BASE_URL . "/posts/{$postId}";
        return $this->makeRequest($url, 'PUT', $data);
    }

    public function deletePost($postId)
    {
        $url = self::BASE_URL . "/posts/{$postId}";
        return $this->makeRequest($url, 'DELETE');
    }

    private function makeRequest($url, $method = 'GET', $data = null)
    {
        $ch = curl_init($url);

        if ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($statusCode === 200) {
            return json_decode($response, true);
        } else {
            return ["error" => "Request failed with status code {$statusCode}"];
        }
    }
}


$api = new JsonPlaceholderAPI();

// Получение списка пользователей
$users = $api->getUsers();
echo "Список пользователей:\n";
foreach ($users as $user) {
    echo "ID: {$user['id']}, Имя: {$user['name']}\n";
}

// Получение постов для пользователя
$userId = 1;
$userPosts = $api->getUserPosts($userId);
echo "Посты пользователя с ID={$userId}:\n";
foreach ($userPosts as $post) {
    echo "ID: {$post['id']}, Заголовок: {$post['title']}\n";
}

// Создание нового поста
$newPostData = [
    "userId" => 1,
    "title" => "Новый пост",
    "body" => "Текст нового поста",
];
$createdPost = $api->createPost($newPostData);
echo "Созданный пост: " . json_encode($createdPost) . "\n";

// Обновление поста
$updatedPostData = [
    "title" => "Обновленный пост",
    "body" => "Обновленный текст поста",
];
$updatedPost = $api->updatePost(1, $updatedPostData);
echo "Обновленный пост: " . json_encode($updatedPost) . "\n";

// Удаление поста
$result = $api->deletePost(1);
echo json_encode($result) . "\n";
