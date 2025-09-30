<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../Models/AdminModel.php';

class AdminController extends BaseController
{
    private $adminModel;
    private $walletModel;
    private $msgModel;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->adminModel = new AdminModel($db);

    }
    public function login()
    {
      if (isset($_SESSION['admin_id'])) {
            //header("Location: /msme/admin/adminpages/dashboard.php");
            header("Location: " . SITE_URL . "admin/views/dashboard.php");

            exit();
        }
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        include __DIR__ . '/../views/login.php';
    }
  public function loginAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $admin = $this->adminModel->adminLogin($email);
            if ($admin && password_verify($password, $admin['admin_password'])) {
                session_start();

                $authKey = bin2hex(random_bytes(32));
                $_SESSION['authKey'] = $authKey;
                $_SESSION['admin_id'] = $admin['admin_id'];
                header("X-Auth-Key: $authKey");
                header("Location: " . SITE_URL . "admin/index.php?action=dashboard");
                exit;
            } else {
                $_SESSION['error'] = "Invalid email or password!";
                header("Location: " . SITE_URL . "admin/index.php");
                exit;

            }
        }
    }
public function logout(): void
  {
    session_start();
    $_SESSION = [];
    session_destroy();
    header("Location: " . SITE_URL . "admin/index.php?action=login");
    exit;
  }
}
?>
