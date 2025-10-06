<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>States</title>
</head>
<body>
<div class="content">
    <h2>States</h2>
     <div style="text-align:right;">
        <a href="index.php?action=new_state" class="new-btn">+ New State</a>
    </div>
    <table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Code</th>
                <th>Zone id</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i=1;
            foreach($states as $row){
               ?>
                <tr>
                  <td>
                    <?php echo $i++; ?>
                  </td>
                    <td><?php echo $row['state']; ?></td>
                    <td><?php echo $row['statecode']; ?></td>
                    <td><?php echo $row['zoneid']; ?></td>
            <td>
              <a href="index.php?action=edit_state&id=<?php echo $row['stateid']; ?>"   class="btn btn-success btn-sm">Edit</a>
              <a href="javascript:void(0)" onclick="deletestate(<?php echo $row['stateid']; ?>)" class="action-link remove-link btn btn-danger">Remove</a>
              </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function deletestate(stateid) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This state will be permanently deleted ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo SITE_URL; ?>admin/index.php?action=remove_state",
                type: "POST",
                data: { stateid: stateid },
                dataType: "json",
                success: function(response) {
                      console.log("AJAX Response:", response);
                    if (response.success) {
                        $("#row-" + stateid).remove();
                        Swal.fire(
                            'Deleted!',
                            'State has been removed.',
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
                      console.log("AJAX ERROR RESPONSE:", xhr.responseText); // 🔍 debug output

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
