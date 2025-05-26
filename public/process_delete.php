<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'functions.php';
is_not_logged_in();

$editedUserId = (int)$_GET['id'];

if ($_SESSION['role'] === 'admin') {
    delete($editedUserId);
    setFlashMessage('success', 'Пользователь успешно удален');
    if ($editedUserId === $_SESSION['current_user_id']){
        redirectTo('page_register.php');
    }
    redirectTo('users.php');
} elseif ($editedUserId === $_SESSION['current_user_id']){
    delete($editedUserId);
    setFlashMessage('success', 'Пользователь успешно удален');
    redirectTo('page_register.php');
    log_out();
} else {
    setFlashMessage('danger', 'Можно редактировать только свой профиль');
    redirectTo('users.php');
}



