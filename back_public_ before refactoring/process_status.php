<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'functions.php';

is_not_logged_in();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Проверяем, был ли запрос отправлен методом POST
    setFlashMessage('danger', 'запрос отправлен не методом post или не был отправлен вообще');
    redirectTo('users.php');
}


$status = $_POST['status'];
$editedUserId = (int)$_POST['id'];





if (set_status($editedUserId, $status)) {
    setFlashMessage('success', 'Профиль успешно обновлен');
    redirectTo('page_profile.php');
} else {
    setFlashMessage('danger', 'Что-то пошло не так');
    redirectTo('page_profile.php');

}

