<?php
session_start();
// Import autoload
use TinyFramework\App;

require_once __DIR__ . '/../vendor/autoload.php';

// Un mask permission
umask(0);
$app = new App(true);
$app->run();

