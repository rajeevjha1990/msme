<?php
include 'dbconfigf/dbconst2025.php';

$sql = "SELECT stateid, state FROM state_master ORDER BY state";
$result = $conn->query($sql);

$states = [];
while ($row = $result->fetch_assoc()) {
    $states[] = $row;
}

echo json_encode($states);
?>
