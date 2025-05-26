<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'functions.php';

$email = $_POST['email'];
$password = $_POST['password'];

$user = getUserByEmail($email);
if ($user != false) {
    setFlashMessage('danger', 'Этот эл. адрес уже занят другим пользователем');
    redirectTo('page_register.php');
}

if (addUser($email, $password)){
    setFlashMessage('success', 'Регистрация успешна');
    redirectTo('page_login.php');
} else {
    setFlashMessage('danger', 'Произошла ошибка во время добавления пользователя');
    redirectTo('page_register.php');
}



//setFlashMessage('success', 'Регистрация успешна');
//redirectTo('page_login.php');