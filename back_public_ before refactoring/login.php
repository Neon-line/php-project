<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'functions.php';

$email = $_POST['email'];
$password = $_POST['password'];

if (login($email, $password)) {
    redirectTo('users.php');
} else {
    setFlashMessage('danger', 'Имя не найдено или пароль не верный');
    redirectTo('page_login.php');
}



