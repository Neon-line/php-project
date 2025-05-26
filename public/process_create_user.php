<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Проверяем, был ли запрос отправлен методом POST
    setFlashMessage('danger', 'запрос отправлен не методом post или не был отправлен вообще');
    redirectTo('create_user.php');
}

$email = $_POST['email'];
$password = $_POST['password'];
$username = $_POST['username'];
$job_title = $_POST['job_title'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$status = $_POST['status'];
$instagram = $_POST['instagram'];
$vk = $_POST['vk'];
$telegram = $_POST['telegram'];
$image = $_FILES['image'];



if (!is_user_email_free($email)) {
    setFlashMessage('danger', 'Этот эл. адрес уже занят другим пользователем');
    redirectTo('create_user.php');
}

$userId = addUser($email, $password);

edit_general_info($userId, $username, $job_title, $phone, $address);
set_status($userId, $status);

$result = upload_avatar($userId, $image);
if ($result){
    
} elseif ($result === 'UPLOAD_ERR_NO_FILE') {
    setFlashMessage('danger', 'Ошибка при загрузке файла!');
    redirectTo('create_user.php');
} elseif ($result === 'error_uploading'){
    setFlashMessage('danger', 'Ошибка при загрузке файла!');
    redirectTo('create_user.php');
} elseif ($result === 'format_error'){
    setFlashMessage('danger', 'Можно загружать файлы только в формате: jpg, png');
    redirectTo('create_user.php');
} elseif ($result == false){
    setFlashMessage('danger', 'Новое фото не загружено');
    redirectTo('create_user.php');
}


setFlashMessage('success', 'Пользователь добавлен');
redirectTo('users.php');

