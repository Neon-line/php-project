<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'functions.php';
is_not_logged_in();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Проверяем, был ли запрос отправлен методом POST
    setFlashMessage('danger', 'запрос отправлен не методом post или не был отправлен вообще');
    redirectTo('edit.php');
}

$username = $_POST['username'];
$job_title = $_POST['job_title'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$editedUserId = (int)$_POST['id'];

if ($_SESSION['role'] === 'admin' or is_author($_SESSION['current_user_id'], $editedUserId)){
    if (!empty(get_user_by_id($editedUserId))){
        edit_general_info($editedUserId, $username, $job_title, $phone, $address);
        setFlashMessage('success', 'Профиль успешно обновлен');
        redirectTo('users.php');
    } else {
        setFlashMessage('danger', 'Редактируемый пользователь не найден');
        redirectTo('users.php');
    }
} else {
    setFlashMessage('danger', 'Можно редактировать только свой профиль');
    redirectTo('users.php');
}