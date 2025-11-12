<?php
namespace services;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../services/SessionService.php';
use services\SessionService;

$session = SessionService::getInstance();

$session->logout();

header('Location: ../../index.php');
exit;