<?php
require_once('../utils/session.php');
$session = new Session();
$session->logout();

die(header('Location: ../pages/login.php'));