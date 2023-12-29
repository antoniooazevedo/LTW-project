<?php 
require_once "../utils/session.php";
require_once "../database/faq.php";


$session = new Session();
$session->generateToken();

if ($session->getUsername() == null) die(header('Location: /../pages/login.php'));

$faq = new FAQ();
$faqs = $faq->getAllFAQ();

echo json_encode($faqs);
?>