<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../dbconfigf/dbconst2025.php';
require_once __DIR__ . '/Adminapi/AdminController.php';
require_once __DIR__ . '/Adminapi/DashboardController.php';
require_once __DIR__ . '/Adminapi/CommonController.php';


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
  case 'bcategories':
    $commonController = new CommonController($conn);
    $commonController->businessCategories();
    break;
  case 'new_category':
    $commonController = new CommonController($conn);
    $commonController->new_category();
    break;
  case 'edit_category':
    $commonController = new CommonController($conn);
    $id = $_GET['id'] ?? null;
    if ($id) {
      $commonController->edit_category($id);
    } else {
      echo "ID missing!";
    }
    break;
    case 'add_category':
    $commonController = new CommonController($conn);
    $commonController->add_category();
    break;

    case 'remove_category':
    $commonController = new CommonController($conn);
    $commonController->remove_category();
    break;

    case 'states':
    $commonController = new CommonController($conn);
    $commonController->states();
    break;
    case 'edit_state':
      $commonController = new CommonController($conn);
      $id = $_GET['id'] ?? null;
      if ($id) {
        $commonController->edit_state($id);
      } else {
        echo "ID missing!";
      }
      break;
    case 'new_state':
    $commonController = new CommonController($conn);
    $commonController->new_state();
    break;
    case 'add_state':
    $commonController = new CommonController($conn);
    $commonController->add_state();
    break;
    case 'remove_state':
    $commonController = new CommonController($conn);
    $commonController->remove_state();
    break;
    case 'cities':
    $commonController = new CommonController($conn);
    $commonController->cities();
    break;
  default:
    echo "404 - Page Not Found";
    break;
}
?>
