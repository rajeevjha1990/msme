<?php
require_once(__DIR__ . '/../../dbconfigf/dbconst2025.php');
require_once(__DIR__ . '/../../dbconfigf/dbconn.php');
class AdminModel
{
    private $db;
    private $conn;

  public function __construct($db)
    {
        $this->db = new DBConn($db); // DBConn object initialize
        $this->conn = $this->db->conn; // mysqli connection

    }
  public function adminLogin($email)
  {
      return $this->db->fetchSingle("admin", "admin_email='" . $this->db->conn->real_escape_string($email) . "'");
  }
public function getAdminData($adminId)
  {
    return $this->db->fetchSingle("admin", "admin_id='" . $this->db->conn->real_escape_string($adminId) . "'");
  }
}
?>
