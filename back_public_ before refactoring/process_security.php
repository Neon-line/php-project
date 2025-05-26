<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'functions.php';

is_not_logged_in();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Проверяем, был ли запрос отправлен методом POST
    setFlashMessage('danger', 'запрос отправлен не методом post или не был отправлен вообще');
    redirectTo('security.php');
}

$email = $_POST['email'];
$password = $_POST['password'];
$editedUserId = (int)$_POST['id'];

$user_by_id = get_user_by_id($editedUserId);
$email_by_id = $user_by_id['email'];

if (is_user_email_free($email) or ($email === $_SESSION['email']) or ($email === $email_by_id)){
    if (edit_credentials($editedUserId, $email, $password)){
        setFlashMessage('success', 'Профиль успешно обновлен');
        if ($_SESSION['role'] === 'admin'){
            redirectTo('users.php');
        } else {
            redirectTo('page_profile.php');
        }
    } else {
        setFlashMessage('danger', 'Что-то пошло не так. Внесено 0 изменений в бд');
        redirectTo('security.php?id=' . "{$editedUserId}");
    }

} else {
setFlashMessage('danger', 'Email занят');
redirectTo('security.php?id=' . "{$editedUserId}");
}


