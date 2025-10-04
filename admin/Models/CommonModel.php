<?php
require_once(__DIR__ . '/../../dbconfigf/dbconst2025.php');
require_once(__DIR__ . '/../../dbconfigf/dbconn.php');
class CommonModel
{
    private $db;
    private $conn;

  public function __construct($db)
    {
        $this->db = new DBConn($db); // DBConn object initialize
        $this->conn = $this->db->conn; // mysqli connection

    }

public function business_categories()
  {
    return $this->db->fetch("business_categories", "status=1", "id", "ASC");
  }
public function business_category($categoryid)
  {
    return $this->db->fetchSingle("business_categories", "id='" . $this->db->conn->real_escape_string($categoryid) . "'");
  }
  public function insertcategory($data)
 {
    $fields = [];
    foreach ($data as $key => $value) {
        $fields[] = "$key='" . $this->db->conn->real_escape_string($value) . "'";
    }
    $string = implode(", ", $fields);
        return $this->db->insertSet("business_categories", $string);
  }
public function getcategory($id)
{
    $id = (int)$id;
    $slide = $this->db->fetchSingle('business_categories', "id = $id");
    if ($slide) {
        return $slide;
    } else {
        return false;
    }
  }
public function updatecategory($id, $data)
  {
    $id = (int)$id;
    $set = [];
    foreach($data as $key => $val){
        $val =$this->db->conn->real_escape_string($val);
        $set[] = "$key='$val'";
    }
    $table = "business_categories";
    $sql = "UPDATE $table SET " . implode(',', $set) . " WHERE id=$id";
    return $this->db->conn->query($sql);
  }
public function remove_category($id)
  {
    return $this->db->softDelete("business_categories", "id='" . intval($id) . "'");
  }
}
?>
