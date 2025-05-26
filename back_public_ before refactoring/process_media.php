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


$editedUserId = (int)$_POST['id'];
$image = $_FILES['image'];

upload_avatar($editedUserId, $image);

setFlashMessage('success', 'Профиль успешно обновлен');
redirectTo('page_profile.php');

