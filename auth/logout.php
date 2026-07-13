<?php
require_once __DIR__ . '/../includes/functions.php';

// Session komplett leeren und zerstören
$_SESSION = [];
session_destroy();

header("Location: login.php");
exit;