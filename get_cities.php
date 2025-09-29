<?php
include 'dbconfigf/dbconst2025.php';
$stateid = intval($_GET['stateid']); // passed from frontend
$sql = "SELECT cityid, city FROM city_master WHERE stateid = $stateid ORDER BY city";
$result = $conn->query($sql);

$cities = [];
while ($row = $result->fetch_assoc()) {
    $cities[] = $row;
}

echo json_encode($cities);
?>
