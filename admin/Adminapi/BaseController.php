<?php
require_once __DIR__ . '/../Models/AdminModel.php';

class BaseController
{
    protected $db;
    private $adminModel;
    protected $loggedAdmin;


    public function __construct($db = null)
    {
        $this->db = $db;
        $this->adminModel = new AdminModel($db);
        $this->startSession();
        if (isset($_SESSION['admin_id'])) {
            $adminId = $_SESSION['admin_id'];
            $this->loggedAdmin = $this->adminModel->getAdminData($adminId);
        }

    }
public function getLoggedAdmin() {
        return $this->loggedAdmin;
    }
    protected function view($file, $data = [])
    {
        extract($data);
        include __DIR__ . '/../Views/' . $file . '.php';
    }

    protected function redirect($url)
    {
        header("Location: $url");
        exit();
    }

    protected function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}
