<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Europe/Paris');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/mongodb.php';

require_once __DIR__ . '/../src/Views/core/Model.php';
require_once __DIR__ . '/../src/Views/core/Controller.php';
require_once __DIR__ . '/../src/Views/core/Router.php';

$router = new Router();
$router->route();