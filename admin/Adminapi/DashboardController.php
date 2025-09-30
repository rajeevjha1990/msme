<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../Models/AdminModel.php';

// SITE_URL already defined check
if (!defined('SITE_URL')) {
    define("SITE_URL", "http://localhost/msme/");
}

class DashboardController extends BaseController
{
    private $adminModel;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->adminModel = new AdminModel($db);
    }

    public function dashboard()
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: " . SITE_URL . "admin/index.php?action=login");
            exit;
        }

        $loggedAdmin = $this->getLoggedAdmin();

        include __DIR__ . "/../includes/header.php";
        include __DIR__ . "/../includes/sidebar.php";
        include __DIR__ . '/../views/dashboard.php';
        include __DIR__ . "/../includes/footer.php";
    }
}
?>
