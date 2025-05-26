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

$result = upload_avatar($editedUserId, $image);
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

setFlashMessage('success', 'Профиль успешно обновлен');
redirectTo('page_profile.php');

