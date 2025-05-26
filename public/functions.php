<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// РЕГИСТРАЦИЯ 

function setFlashMessage($name, $message) {
    $_SESSION[$name] = $message;
}

function displayFlashMessage($name) {
    if(isset($_SESSION[$name])) {
        echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
        unset($_SESSION[$name]);
    }
}

function redirectTo($path) {
    header("Location: $path");
    exit;
}

function getUserByEmail($email) {
    $conn = new PDO("mysql:host=mysql;dbname=student", 'user', 'user');
    $sql = "SELECT * FROM users WHERE email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["email" => $email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($result)){
        return $result;
    } elseif (empty($result)) {
        return false;
    } else{
        return false;
    }
}

function addUser($email, $password) {
    // Возвращает id последнего измененного пользователя
    try {
        $conn = new PDO("mysql:host=mysql;dbname=student", 'user', 'user');
        $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT), 'role' => 'user']);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        error_log("Ошибка добавления пользователя: " . $e->getMessage());
        return false;
    }
    
}

// АВТОРИЗАЦИЯ 

function login($email, $password) {

    $user = getUserByEmail($email);
    if ($user and password_verify($password, $user['password'])) {
        $_SESSION['current_user_id'] = $user['id'];      
        $_SESSION['email'] = $user['email']; 
        $_SESSION['logged_in'] = true;
        $_SESSION['role'] = $user['role'];
        return true;    
    } else {
        return false;
    }

}

function get_all_users(){
    $conn = new PDO("mysql:host=mysql;dbname=student", 'user', 'user');
    $sql = "SELECT * FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

//

function is_not_logged_in(){
    if (!isset($_SESSION['logged_in'])){
        redirectTo('page_login.php');
    }
}

function is_not_admin(){
    if (!$_SESSION['role'] === 'admin'){
        return true;
    } else {
        return false;
    }
}

function show_status($status){
    if ($status === 'online'){
        return "\"status status-success mr-3\"";

    } elseif ($status === 'away'){
        return "\"status status-warning mr-3\"";
        
    } elseif ($status === 'do not disturb') {
        return "\"status status-error mr-3\"";
    }
}

function edit_general_info($userId, $username, $job_title, $phone, $address){
        $conn = new PDO("mysql:host=mysql;dbname=student", 'user', 'user');
        $sql = "UPDATE users
                SET username = :username, job_title = :job_title, phone = :phone, address = :address
                WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $userId, 'username' => $username, 'job_title' => $job_title, 'phone' => $phone, 'address' => $address]);
        return $stmt->rowCount();
}

function set_status($userId, $status){
    $conn = new PDO("mysql:host=mysql;dbname=student", 'user', 'user');
    $sql = "UPDATE users SET status = :status WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $userId, 'status' => $status]);
    return $stmt->rowCount() > 0;

}

function upload_avatar($userId, $image){
    if (!empty($image) && $image['error'] == UPLOAD_ERR_OK) {
        $filename = uniqid() . '_' . basename($image['name']); // Получаем имя файла
        $filetype = $image['type']; // Получаем MIME-тип файла
        $tmp_name = $image['tmp_name']; // Получаем временный путь к файлу

        // Проверяем тип
        $allowed_types = ['image/jpeg', 'image/png']; // Создаем массив с допустимыми MIME-типами
        if (!in_array($filetype, $allowed_types)) { // Если тип файла не входит в список допустимых...
            $error = 'format_error';
            return $error;
        }
        // перемещаем файл на сервер из временного хранилища
        $target_dir = 'uploads/'; // Путь к папке для загрузки файлов
        $target_file = $target_dir . basename($filename); // Формируем полный путь к файлу
        if (!move_uploaded_file($tmp_name, $target_file)) { // Перемещаем файл из временной папки в папку uploads
            $error = 'error_uploading';
            return $error;
        }

        $conn = new PDO("mysql:host=mysql;dbname=student", 'user', 'user');

        // Удаляем старый аватар с сервера
        $sql = "SELECT image FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $userId]);
        $oldImage = $stmt->fetchColumn();
        if ($oldImage && file_exists($oldImage)) {
            unlink($oldImage);
        }

        // Загружаем новый аватар
        $sql = "UPDATE users SET image = :image WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['image' => $target_file, 'id' => $userId]);
        return $stmt->rowCount() > 0;

    } elseif ($image['error'] != UPLOAD_ERR_NO_FILE) {
        $error = 'UPLOAD_ERR_NO_FILE';
        return $error;
    }
    return ''; 
}

function has_image($editedUserId){
    $conn = new PDO("mysql:host=mysql;dbname=student", 'user', 'user');
    $sql = "SELECT * FROM users WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["id" => $editedUserId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($result)){
        return true;
    } else{
        return false;
    }

}

function add_social_links($userId, $telegram, $instagram, $vk){
    $conn = new PDO("mysql:host=mysql;dbname=student", 'user', 'user');
    $sql = "UPDATE users SET
            telegram = :telegram, instagram = :instagram, vk = :vk
            WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $userId, 'telegram' => $telegram, 'instagram' => $instagram, 'vk' => $vk]);
    return $stmt->rowCount();
}

function is_user_email_free($email){
    $conn = new PDO("mysql:host=mysql;dbname=student", 'user', 'user');
    $sql = "SELECT * FROM users WHERE email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["email" => $email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($result)){
        return true;
    } else{
        return false;
    }

}

function is_author($logged_user_id, $edit_user_id){
    if ($logged_user_id === $edit_user_id){
        return true;
    } else {
        return false;
    }
}

function get_user_by_id($id){
    $conn = new PDO("mysql:host=mysql;dbname=student", 'user', 'user');
    $sql = "SELECT * FROM users WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;

}

function edit_credentials($editedUserId, $email, $password){
        $conn = new PDO("mysql:host=mysql;dbname=student", 'user', 'user');
        
        $sql = "UPDATE users SET email = :email";
        $params = ['id' => $editedUserId, 'email' => $email];

        // Если пароль не пустой, добавляем его в запрос
        if (!empty($password)) {
            $sql .= ", password = :password";
            $params['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $sql .= " WHERE id = :id"; 
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount() > 0;

}

function delete($user_id){
    $conn = new PDO("mysql:host=mysql;dbname=student", 'user', 'user');
    $sql = "DELETE FROM users WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["id" => $user_id]);
    return $stmt->rowCount() > 0;
}

function log_out(){
    session_unset();
    session_destroy();
}