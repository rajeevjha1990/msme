<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../dbconfigf/dbconst2025.php';
require_once __DIR__ . '/Adminapi/AdminController.php';
require_once __DIR__ . '/Adminapi/DashboardController.php';


$adminController = new AdminController($conn);
$action = $_GET['action'] ?? 'dashboard';
// Routing
switch ($action) {
  case 'login':
    $adminController->login();
    break;
  case 'loginAction':
    $adminController->loginAction();
    break;
  case 'logout':
    $adminController->logout();
    break;
  case 'dashboard':
    $dashboardController = new DashboardController($conn);
    $dashboardController->dashboard();
    break;
  default:
    echo "404 - Page Not Found";
    break;
}
?>
