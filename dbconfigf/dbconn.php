<?php
include_once 'dbconst2025.php';
date_default_timezone_set('Asia/Kolkata');

define('DATE_FORMAT', 'd M Y');
define('DATE_TIME_FORMAT', 'd M Y, h:i:s A');

class DBConn
{
    public $conn;
    private $ownConnection = false; // Track if we created connection

    function __construct($conn = null, $type = "web")
    {
        if ($conn instanceof mysqli) {
            $this->conn = $conn;
            $this->ownConnection = false; // Connection passed from outside
        } elseif ($type == "web") {
            $this->conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
            $this->ownConnection = true; // Connection created inside
        }

        mysqli_query($this->conn, "SET character_set_results='utf8'");
        mb_language('uni');
        mb_internal_encoding('UTF-8');
        mysqli_query($this->conn, "set names 'utf8'");
    }

    function __destruct()
    {
        if ($this->ownConnection && $this->conn) {
            $this->conn->close();
        }
    }

    function insertSet($table, $string)
    {
        $sql = "INSERT INTO " . $table . " SET " . $string;
        if ($this->conn->query($sql) === TRUE)
            return $this->conn->insert_id;
        else
            return false;
    }

    function updateTable($table, $string, $cond = "")
    {
        $sql = "UPDATE " . $table . " SET " . $string;
        if ($cond <> "")
            $sql .= " WHERE " . $cond;

        if ($this->conn->query($sql) === TRUE)
            return true;
        else
            return false;
    }

    function countRows($table, $cond = "")
    {
        $sql = "SELECT * FROM " . $table;
        if ($cond <> "")
            $sql .= " WHERE " . $cond;

        $res = $this->conn->query($sql);
        return $res->num_rows;
    }

    function fetch($table, $cond = "", $order = "", $ord_type = "", $limit = "")
    {
        $sql = "SELECT * FROM " . $table;
        if ($cond <> "")
            $sql .= " WHERE " . $cond;
        if ($order <> "")
            $sql .= " ORDER BY " . $order . " " . $ord_type;
        if ($limit <> "")
            $sql .= " LIMIT " . $limit;

        $res = $this->conn->query($sql);

        if ($res && $res->num_rows > 0) {
            unset($resarr);
            while ($arr = $res->fetch_assoc())
                $resarr[] = $arr;
            return $resarr;
        }
        return false;
    }

    function fetchRand($table, $cond = "", $order = "", $ord_type = "RAND()", $limit = "")
    {
        $sql = "SELECT * FROM " . $table;
        if ($cond <> "")
            $sql .= " WHERE " . $cond;
        if ($order <> "")
            $sql .= " ORDER BY " . $ord_type;
        if ($limit <> "")
            $sql .= " LIMIT " . $limit;

        $res = $this->conn->query($sql);

        if ($res && $res->num_rows > 0) {
            unset($resarr);
            while ($arr = $res->fetch_assoc())
                $resarr[] = $arr;
            return $resarr;
        }
        return false;
    }

    function pagination($table, $cond = "", $order = "", $ord_type = "ASC", $start_from = "", $limit = "")
    {
        $sql = "SELECT * FROM " . $table;
        if ($cond <> "")
            $sql .= " WHERE " . $cond;
        if ($order <> "")
            $sql .= " ORDER BY " . $order . " " . $ord_type;
        if ($start_from <> "")
            $sql .= " LIMIT " . $start_from . ", " . $limit;

        $res = $this->conn->query($sql);

        if ($res && $res->num_rows > 0) {
            unset($resarr);
            while ($arr = $res->fetch_assoc())
                $resarr[] = $arr;
            return $resarr;
        }
        return false;
    }

    function fetchLike($table, $colmn, $like, $order = "", $ord_type = "DESC", $limit = "")
    {
        $sql = "SELECT * FROM " . $table;
        if ($colmn <> "")
            $sql .= " WHERE " . $colmn . " LIKE '" . $like . "%'";
        if ($order <> "")
            $sql .= " ORDER BY " . $order . " " . $ord_type;
        if ($limit <> "")
            $sql .= " LIMIT " . $limit;

        $res = $this->conn->query($sql);

        if ($res && $res->num_rows > 0) {
            unset($resarr);
            while ($arr = $res->fetch_assoc())
                $resarr[] = $arr;
            return $resarr;
        }
        return false;
    }

    function fetchDesc($table, $cond = "", $order = "", $ord_type = "DESC", $limit = "")
    {
        return $this->fetch($table, $cond, $order, $ord_type, $limit);
    }

    function fetchSingle($table, $cond = "", $order = "", $ord_type = "ASC")
    {
        $result = $this->fetch($table, $cond, $order, $ord_type, "1");
        return $result ? $result[0] : false;
    }

    function fetchSingleDesc($table, $cond = "", $order = "", $ord_type = "DESC")
    {
        return $this->fetchSingle($table, $cond, $order, $ord_type);
    }

    function deleteRecord($table, $cond = "")
    {
        $sql = "DELETE FROM " . $table;
        if ($cond <> "")
            $sql .= " WHERE " . $cond;
        return $this->conn->query($sql) === TRUE;
    }

    function getLastid($table)
    {
        return $this->getLast($table);
    }

    function getLast($table, $cond = "")
    {
        $sql = "SELECT * FROM " . $table;
        if ($cond <> "")
            $sql .= " WHERE " . $cond;
        $sql .= " ORDER BY `id` DESC LIMIT 1";
        $res = $this->conn->query($sql);

        return ($res && $res->num_rows > 0) ? $res->fetch_assoc() : false;
    }

    function executeQuery($sql)
    {
        return $this->conn->query($sql);
    }

    function getElement($table, $col, $cond = "")
    {
        $sql = "SELECT " . $col . " FROM " . $table;
        if ($cond != "")
            $sql .= " WHERE " . $cond;

        $val = $this->conn->query($sql)->fetch_assoc();
        return $val[$col] ?? null;
    }

    // Soft delete
    function softDelete($table, $cond = "")
    {
        if ($cond == "")
            return false;

        $sql = "UPDATE " . $table . " SET status = 0 WHERE " . $cond;
        return $this->conn->query($sql) === TRUE;
    }
}
?>
