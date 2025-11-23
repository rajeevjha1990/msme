<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users</title>
</head>
<body>
<div class="content">
    <h2>All Users</h2>
     <!-- <div style="text-align:right;">
        <a href="index.php?action=new_industry" class="new-btn">+ New Industry</a>
    </div> -->
    <table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Referance Id</th>
                <th>Name</th>
                <th>Industry</th>
                <th>Category</th>
                <th>Whatsapp</th>
                <th>Email</th>
                <th>View Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
              $i=1;
              foreach($allusers as $row){
              ?>
                <tr>
                  <td><?php echo $i++; ?></td>
                    <td><?php echo $row['reference_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['industry']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['whatsapp']; ?></td>
                    <td><?php echo $row['email']; ?></td>
            <td>
                <a href="index.php?action=user_details&id=<?php echo $row['user_id']; ?>" class="btn btn-success btn-sm">
                  <i class="fa-solid fa-eye"></i>
                </a>
              </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
