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



is_email_free(getUserByEmail($email));

$userId = addUser($email, $password);

edit_general_info($userId, $username, $job_title, $phone, $address);
set_status($userId, $status);
upload_avatar($userId, $image);

setFlashMessage('success', 'Пользователь добавлен');
redirectTo('users.php');

