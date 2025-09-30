<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users</title>
</head>
<body>
<div class="content">
    <h2>User Data</h2>
    <table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>User ID</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($users as $row){
               ?>
                <tr>
                    <td>
                      <a href="index.php?action=view_user&id=<?php echo $row['userid']; ?>">
                      <?php echo $row['userid']; ?>
                    </a>
                    </td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['balance']; ?></td>
                    <td>
    <?php
    // Status label
    if ($row['status'] == 1) {
        echo "Active";
    } elseif ($row['status'] == 2) {
        echo "Inactive";
    } elseif ($row['status'] == 0) {
        echo "Blocked";
    }
    ?>
</td>
              <td>
                  <?php
                  // Action buttons based on current status
                  if ($row['status'] == 1) { // Active
                      echo '<a href="javascript:void(0)" onclick="changeUserStatus(\''.$row['userid'].'\', 2)">Set Inactive</a> | ';
                      echo '<a href="javascript:void(0)" onclick="changeUserStatus(\''.$row['userid'].'\', 0)">Block</a>';
                  } elseif ($row['status'] == 2) { // Inactive
                      echo '<a href="javascript:void(0)" onclick="changeUserStatus(\''.$row['userid'].'\', 1)">Activate</a> | ';
                      echo '<a href="javascript:void(0)" onclick="changeUserStatus(\''.$row['userid'].'\', 0)">Block</a>';
                  } elseif ($row['status'] == 0) { // Blocked
                      echo '<a href="javascript:void(0)" onclick="changeUserStatus(\''.$row['userid'].'\', 1)">Activate</a> | ';
                      echo '<a href="javascript:void(0)" onclick="changeUserStatus(\''.$row['userid'].'\', 2)">Set Inactive</a>';
                  }
                  ?>
              </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function changeUserStatus(userid, status) {
    let actionText = '';
    if (status == 1) actionText = 'activate';
    else if (status == 0) actionText = 'deactivate';
    else if (status == 2) actionText = 'block';

    Swal.fire({
        title: 'Are you sure?',
        text: `This will ${actionText} the user!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo SITE_URL; ?>admin/index.php?action=change_user_status",
                type: "POST",
                data: { userid: userid, status: status },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Updated!',
                            response.message,
                            'success'
                        ).then(() => location.reload());
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message || 'Something went wrong.',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        'AJAX error: ' + error,
                        'error'
                    );
                }
            });
        }
    });
}

function deleteUser(userid) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This user will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo SITE_URL; ?>admin/index.php?action=delete_user",
                type: "POST",
                data: { userid: userid },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#row-" + userid).remove();
                        Swal.fire(
                            'Deleted!',
                            'User has been removed.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message || 'Something went wrong.',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        'AJAX error: ' + error,
                        'error'
                    );
                }
            });
        }
    });
}
</script>

</body>
</html>
