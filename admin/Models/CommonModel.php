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
    $category = $this->db->fetchSingle('business_categories', "id = $id");
    if ($category) {
        return $category;
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
  public function get_states()
{
    $sql = "SELECT * FROM state_master WHERE status=1 ORDER BY state ASC";
    $res = $this->conn->query($sql);

    $data = [];
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}
public function get_state($id)
{
    $id = (int)$id;
    $state = $this->db->fetchSingle('state_master', "stateid = $id");
    if ($state) {
        return $state;
    } else {
        return false;
    }
  }
public function insertstate($data)
   {
      $fields = [];
      foreach ($data as $key => $value) {
          $fields[] = "$key='" . $this->db->conn->real_escape_string($value) . "'";
      }
      $string = implode(", ", $fields);
          return $this->db->insertSet("state_master", $string);
    }
    public function updatestate($id, $data)
      {
        $id = (int)$id;
        $set = [];
        foreach($data as $key => $val){
            $val =$this->db->conn->real_escape_string($val);
            $set[] = "$key='$val'";
        }
        $table = "state_master";
        $sql = "UPDATE $table SET " . implode(',', $set) . " WHERE stateid=$id";
        return $this->db->conn->query($sql);
      }
    public function remove_state($id)
      {
        return $this->db->softDelete("state_master", "stateid='" . intval($id) . "'");
      }

}
?>
